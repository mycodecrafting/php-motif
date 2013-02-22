# Motif

Motif is a template engine for PHP. Templates are compiled to native PHP. This compilation step can be cached for faster performance.

## Quick Start Guide

```php
<?php
// Inlcude Motif library
require_once 'lib/Motif.php';

// Setup Motif
Motif_Template::setCompilationDir('/tmp');

// Create template instance
$template = new Motif_Template($useCache = false);

// Set template file
$template->setTemplate('/path/to/template.html');

// Add some vars to the template
$template->setVar('speed', 'quick');
$template->setVar('animal', 'fox');
$template->setVar('object', 'fence');

// output template
echo $template->build();
```

**template.html:**

```html
<p>The {speed} brown {animal} jumped over the {object}.</p>
```

**outputs:**

```html
<p>The quick brown fox jumped over the fence.</p>
```

## Motif Template Language

Motif uses XHTML-like tags to form the template language.

All Motif tags are in the **motif** namespace and begin with ``motif:``

**Variables**

  * [Template Variables](#motif-tpl-vars)

**Language Control Structures**

  * [motif:block](#motif-block)
  * [motif:noblock](#motif-noblock)
  * [motif:if](#motif-if)
  * [motif:choose](#motif-choose)
    * [motif:when](#motif-when)
    * [motif:otherwise](#motif-otherwise)
  * [motif:fragment](#motif-fragment)
  * [motif:var](#motif-var)

**Elements**

---

### Template Variables<a id="motif-tpl-vars" />

---

### motif:block<a id="motif-block"/>

Creates an iterative block.

**Syntax:** ``<motif:block var="varName">``

**Example**

```php
<?php
$block = array(
    array('speed' => 'quick', 'animal' => 'fox', 'object' => 'fence'),
    array('speed' => 'slow', 'animal' => 'turkey', 'object' => 'twig'),
);

$template->setVar('animals', $block);
```

```html
<motif:block var="animals">
    <p>The {speed} brown {animal} jumped over the {object}.</p>
</motif:block>
```

**outputs:**

```html
<p>The quick brown fox jumped over the fence.</p>
<p>The slow brown turkey jumped over the twig.</p>
```

---

### motif:noblock<a id="motif-noblock"/>

---

### motif:if<a id="motif-if"/>

Creates a logical if block

**Syntax:** ``<motif:if var="tplVar" [condition="gte" isvar="isvar"] value="somevalue">``

**Syntax:** ``<motif:if var="tplVar" [condition="notexists"]>``

---

### motif:choose<a id="motif-choose"/>

---

### motif:when<a id="motif-when"/>

---

### motif:otherwise<a id="motif-otherwise"/>

---

### motif:fragment<a id="motif-fragment"/>

Places a block of the template into a variable for use elsewhere in the template(s). Useful for blocks that repeat often, such as pagination controls.

The fragment is not parsed until it is re-used elsewhere in the template, and thus, fragements may contain Motif tags and template variables.

**Syntax:** ``<motif:fragment name="varName"> ... </motif:fragment>``

**Tag Attributes:**

  * **name:** the variable name to assign the fragment to

**Exmaple:**

```html
<motif:fragment name="pagination">
    <ul>
    <motif:block var="page.links">
        <motif:choose>
            <motif:when var="current">
                <li class="active">{page}</li>
            </motif:when>
            <motif:otherwise>
                <li><a href="?page={page}" title="Goto page {page}">{page}</a></li>
            </motif:otherwise>
        </motif:choose>
    </motif:block>
    </ul>
</motif:fragment>
```

elsewhere...

```html
{pagination}
<div id="paged-content">
    <!-- content -->
</div>
{pagination}
```

---

### motif:var<a id="motif-var"/>

---

### motif:out

---

### motif:out:nvl

---

### motif:number:format

---

### motif:date:format

---

### motif:array:count

---

### motif:array:join

---

### motif:form

---

### motif:form:checkbox

---

### motif:form:hidden

---

### motif:form:passsword

---

### motif:form:radio

---

### motif:form:select

---

### motif:form:text

---

### motif:form:textarea

---

### motif:include

Include another template into the current template. Useful to include a library of common fragments, or to strucutre re-usable blocks.

**Syntax:** ``<motif:include src="relative/path/to/template.html" />``

**Example:**

```html
<!-- comment: Includes some common UI controls -->
<motif:include src="common/ui/controls.html" />

<!-- pagination fragment defined in common/ui/controls.html -->
{pagination}
<div id="paged-content">
    <!-- content -->
</div>
{pagination}
```

---
