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

<code>destination_folder</code> must be a writable folder.

This would generate a folder <code>/models/</code> in the given destination folder, with Typescript files containing all the models
represented in your symfony project as doctrine entities

so let us imagine we have a single Doctrine Entity in our Project:

``` php
<?php
// src/Acme/UserBundle/Entity/Contact.php

namespace Acme\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="contacts")
 */
class Contact
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nick_name", type="string", length=255, nullable=true)
     */
    private $nickName;
    
}
```

Now if we run the command


``` bash
$ php app/console kif:doctrine:typescript:generate src/typscript
```

we will find the following file gets generated

``` bash
$ src/typscript/models/AcmeUserBundleEntity/Contact.ts
```

with the content


``` typescript

module AcmeUserBundle {

export class Contact {

private _id:number ;

get id(){

return  this._id;

}

set id(_id:number){

this._id=_id;

}

private _nickName:string ;

get nickName(){

return  this._nickName;

}

set nickName(_nickName:string){

this._nickName=_nickName;

}

```


Now you can have access to the generated model easily in your Typescript code


``` typescript

///<reference path="models/AcmeUserBundleEntity/Contact"/>
var contact = new AcmeUserBundleEntity.Contact();
contact.nickName = "myNickname";
alert(contact.nickName);

```


###Usage with option <code>--exposed-only</code>

<strong>Important:</strong> For this to work correctly, you need to have the 
[JMS Serialzer Bundle](https://github.com/schmittjoh/JMSSerializerBundle) installed correctly.

There are cases, where you want to generate only Entites and fields, that are exposed by the JMS serializer,
because you want to keep the rest private or hidden from other parts of your project.

Let us revist the doctrine entity from earlier.

``` php
<?php
// src/Acme/UserBundle/Entity/Contact.php
//...
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

 * @ORM\Table(name="contacts")
  * @ExclusionPolicy("all")
 */
class Contact
{
//...

    /**
     * @Expose
     */
    private $nickName;
    
}
```

Notice the annotation <code>@ExclusionPolicy("all")</code> that excludes all fields, and the annotation <code>@Expose</code>
right over the field <code>$nickName</code>. This would generate the following Typescript file.


``` typescript

module AcmeUserBundle {


private _nickName:string ;

get nickName(){

return  this._nickName;

}

set nickName(_nickName:string){

this._nickName=_nickName;

}

```

Notice how only the field <code>$nickName</code> was generated ,while the rest was ignored. (hidden)

Also note that only entites where <code>@ExclusionPolicy</code> is set as (none or all) are generated. So entites without this
annotation get automatically recognized as <code>@ExclusionPolicy(all)</code> and get completey ignored by the generator


##Todo
* set a <code>--single-file</code> option to a generate all models in a single .ts/js file. 
This should stop the bundle-to-folder mechanism. The name of the generated file would be simply "models.ts"
* when using  <code>--exposed-only</code> option be able to disable the setters when the field is <code>@readOnly</code>

