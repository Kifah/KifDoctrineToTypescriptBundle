<?php


namespace Kif\DoctrineToTypescriptBundle\Service;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use ReflectionClass;

class EntityIterator
{


    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var bool
     */
    private $serializerExposedOnly;

    /**
     * @var bool
     */
    private $singleFile;


    /**
     * @var string
     */
    private $destinationFolder;

    public function __construct(
        EntityManager $em,
        $destinationFolder,
        $serializerExposedOnly = false,
        $singleFile = false
    ) {
        $this->em = $em;
        $this->destinationFolder = $destinationFolder;
        $this->serializerExposedOnly = $serializerExposedOnly;
        $this->singleFile = $singleFile;

    }

    public function entityBundlesIterator()
    {
        /**
         * @var $singleMeta ClassMetadata
         */
        $allMeta = $this->em->getMetadataFactory()->getAllMetadata();
        foreach ($allMeta as $singleMeta) {
            $this->handleSerializerExposed($singleMeta);
            $entities[] = $singleMeta->getName();
        }

    }

    /**
     * creating the final ts file.
     * this would be called from another ts file like this
     * this ///<reference path="Account.ts"/>
     * var account = new KifCrawlBundleEntity.Account();
     * account.email="email@mycompany.com";
     * alert(account.email);
     * @param ClassMetadata $classMetadata
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    //@Todo cleanup code, add option of excluding bundle, or making this for exposed fields/entities only
    protected function  typeScriptCreator(ClassMetadata $classMetadata, $excludedFields = [])
    {

        $reflectionStuff = new \ReflectionClass($classMetadata->getName());
        $name = $reflectionStuff->getShortName();
        $namespace = str_replace("\\", "", $reflectionStuff->getNamespaceName());
        $fields = $classMetadata->getFieldNames();
        $completeFolder = $this->destinationFolder . "models/";
        if (!is_dir($completeFolder)) {
            mkdir($completeFolder);
        }
        if (!is_dir($completeFolder . $namespace . '/')) {
            mkdir($completeFolder . $namespace . '/');
        }
        $file = $completeFolder . $namespace . '/' . $name . '.ts';
        $content = "module $namespace {\n\r";
        $content .= "export class $name {\n\r";


        foreach ($fields as $field) {
            if (!in_array($field, $excludedFields)) {
                $fielType = $this->DoctrineToTypescriptTypeConverter($classMetadata->getFieldMapping($field)['type']);
                $content .= "private _$field$fielType ;\n\r";
                $content .= "get $field(){\n\r";
                $content .= "return  this._$field;\n\r";
                $content .= "}\n\r";
                $content .= "set $field(_$field$fielType){\n\r";
                $content .= "this._$field=_$field;\n\r";
                $content .= "}\n\r";

            }


        }
        $content .= "}\n\r";
        $content .= "}";
        echo "For Doctrine Entity:" . $classMetadata->getName();
        echo "\n\r";
        file_put_contents($file, $content);

    }

    protected function DoctrineToTypescriptTypeConverter($type)
    {

        switch ($type) {
            case "integer":
                return ":number";
                break;
            case "smallint":
                return ":number";
                break;
            case "datetime":
                return ":Date";
                break;
            case "array":
                return ":Array<string>";
                break;
            default:
                return ":" . $type;
        }

    }

    /**
     * @param $singleMeta
     * @param $metData
     */
    protected function handleSerializerExposed(ClassMetadata $singleMeta)
    {
        //if the only-exposed option is not activatated, do normal generation
        if ($this->serializerExposedOnly == false) {
            $this->typeScriptCreator($singleMeta);

        } else {

            $excludedFields = [];
            $fields = $singleMeta->getFieldNames();
            $annotationReader = new AnnotationReader();
            $classAnnotation = $annotationReader->getClassAnnotation(
                $singleMeta->getReflectionClass(),
                ExclusionPolicy::class
            );
            if ($classAnnotation) {
                $exclusionPolicy = $classAnnotation->policy;
                if ($exclusionPolicy == 'ALL') {
                    //filter out the exposed fields only
                    foreach ($fields as $field) {
                        $property = $singleMeta->getReflectionProperty($field);
                        $exposeAnnotation = $annotationReader->getPropertyAnnotation($property, Expose::class);
                        $excludeAnnotation = $annotationReader->getPropertyAnnotation($property, Exclude::class);
                        if (($exposeAnnotation == null && $exclusionPolicy == 'ALL') || ($exclusionPolicy == 'NONE' && $excludeAnnotation != null)) {
                            $excludedFields[] = $field;
                        }

                    }

                }

                // run the generation, only if the complete Entity is not excluded from serializiation
                if (sizeof($excludedFields) < sizeof($fields)) {
                    $this->typeScriptCreator($singleMeta, $excludedFields);
                }

            }


        }

    }

}