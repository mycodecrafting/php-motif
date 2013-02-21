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

## Motif Language Constructs

Motif uses XHTML-like tags to form language constructs.

All Motif tags are in the **motif** namespace and begin with ``motif:``

---

### motif:block

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
