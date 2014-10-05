KifDoctrineToTypescriptBundle
=============================

#Main Concept

This is a command line tool for converting doctrine entities into [Typescript](http://www.typescriptlang.org/) language files.
TypeScript is a typed superset of JavaScript that compiles to plain JavaScript, and heavily uses OOP-Terminolgy and structuring.

A typical Scenario, is if you have a frontEnd based on Typscript/Javascript, and a backend based on symfony/php, and you want to have
a unified DTO/MODEL     representation between both. So if you structure something using doctrine, you want it autoamtically generated
in your javascript part of the project, without needed to write the code for it.



##Installation

``` bash
$ php composer.phar require kif/doctrine_typescript_bundle 'dev-master'
```


* activate the bundle in your <code>app/AppKernel.php</code> file
<br>

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
            new Kif\DoctrineToTypescriptBundle\KifDoctrineToTypescriptBundle(),
    );
}
```


##Usage


###Standard Usage

* in your symfony folder use the command


``` bash
$ php app/console kif:doctrine:typescript:generate destination_folder
```

This would generate a folder <code>/models/</code> in the given destination folder, with Typescript files containing all the models
represented in your symfony project as doctrine entities



##Todo
* set a <code>--destination</code> parameter which changes the default destination folder
and generate only exposed entities or/and variables.
* set a <code>--single-file</code> option to a generate all models in a single .ts/js file. 
This should stop the bundle-to-folder mechanism. The name of the generated file would be simply "models.ts"

