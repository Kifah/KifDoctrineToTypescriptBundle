KifDoctrineToTypescriptBundle
=============================

#Main Concept

This is a command line tool for converting doctrine entities into [Typescript](http://www.typescriptlang.org/) language files.
TypeScript is a typed superset of JavaScript that compiles to plain JavaScript, and heavily uses OOP-Terminolgy and structuring.

A typical Scenario, is if you have a frontEnd based on Typscript/Javascript, and a backend based on symfony/php, and you want to have
a unified DTO/MODEL     representation between both. So if you structure something using doctrine, you want it autoamtically generated
in your javascript part of the project, without needed to write the code for it.



##Installation

* add the following line into your symfony2.2+ <code>composer.json</code> file
<br>
<code>
        "kif/doctrine_typescript_bundle": "dev-master"
</code>
* run <code>composer.phar update</code>
* activate the bundle into your <code>app/AppKernel.php</code> file
<br>
<code>
            new Kif\DoctrineToTypescriptBundle\KifDoctrineToTypescriptBundle(),
</code>


##Usage






##Todo
* set a <code>--destination</code> parameter which changes the default destination folder
and generate only exposed entities or/and variables.
* set a <code>--single-file</code> option to a generate all models in a single .ts/js file. 
This should stop the bundle-to-folder mechanism. The name of the generated file would be simply "models.ts"

