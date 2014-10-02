<?php


namespace Kif\DoctrineToTypescriptBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use ReflectionClass;

class EntityIterator
{


    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;

    }

    public function directoryIterator($exposedOnly=false,$singleFile=false)
    {
        /**
         * @var $singleMeta ClassMetadata
         */
        $allMeta = $this->em->getMetadataFactory()->getAllMetadata();
        foreach ($allMeta as $singleMeta) {
            $metData = $this->em->getClassMetadata($singleMeta->getName());
            $this->typeScriptCreator($metData);
            $entities[] = $singleMeta->getName();
        }

    }

    /**
     * creating the final ts file.
     * this would be called from another ts file like this
     * this ///<reference path="Account.ts"/>
     * var account = new KifCrawlBundleEntity.Account();
     * account.email="faswa";
     * alert(account.email);
     * @param ClassMetadata $classMetadata
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    //@Todo cleanup code, add option of excluding bundle, or making this for exposed fields/entities only
    protected function  typeScriptCreator(ClassMetadata $classMetadata)
    {

        $reflectionStuff = new \ReflectionClass($classMetadata->getName());
        var_dump($classMetadata);die();

        $name = $reflectionStuff->getShortName();
        $namespace=str_replace("\\","",$reflectionStuff->getNamespaceName());
        $fields = $classMetadata->getFieldNames();
        $file = 'generated/'.$name.'.ts';
        $content="module $namespace {\n\r";
        $content.="export class $name {\n\r";



        foreach($fields as $field){
            $fielType=$this->typeConverter($classMetadata->getFieldMapping($field)['type']);
            $content.="private _$field$fielType ;\n\r";
            $content.="get $field(){\n\r";
            $content.="return  this._$field;\n\r";
            $content.="}\n\r";
            $content.="set $field(_$field$fielType){\n\r";
            $content.="this._$field=_$field;\n\r";
            $content.="}\n\r";


        }
        $content.="}\n\r";
        $content.="}";
        file_put_contents($file,$content);

    }

    protected function typeConverter($type) {

        switch ($type) {
            case "integer":
                return  ":number";
                break;
            case "smallint":
                return  ":number";
                break;
            case "datetime":
                return  ":Date";
                break;
            case "array":
                return  ":Array<string>";
                break;
            default:
                return ":".$type;
        }

    }

}