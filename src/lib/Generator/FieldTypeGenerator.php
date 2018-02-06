<?php

namespace Edgar\EzFieldTypeExtra\Generator;

use Sensio\Bundle\GeneratorBundle\Generator\Generator;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\KernelInterface;

class FieldTypeGenerator extends Generator
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * FieldTypeGenerator constructor.
     *
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * Generate new FieldType bundle.
     *
     * @param string $namespace
     * @param string $bundle
     * @param string $dir
     * @param string $fieldTypeName
     * @param string $fieldTypeNamespace
     */
    public function generate(string $namespace, string $bundle, string $dir, string $fieldTypeName, string $fieldTypeNamespace)
    {
        $dir .= '/' . strtr($namespace, '\\', '/');
        if (file_exists($dir)) {
            if (!is_dir($dir)) {
                throw new \RuntimeException(
                    sprintf(
                        'Unable to generate the bundle as the target directory "%s" exists but is a file.',
                        realpath($dir)
                    )
                );
            }

            $files = scandir($dir);
            if ($files != ['.', '..']) {
                throw new \RuntimeException(
                    sprintf(
                        'Unable to generate the bundle as the target directory "%s" is not empty.',
                        realpath($dir)
                    )
                );
            }

            if (!is_writable($dir)) {
                throw new \RuntimeException(
                    sprintf(
                        'Unable to generate the bundle as the target directory "%s" is not writable.',
                        realpath($dir)
                    )
                );
            }
        }

        $basename = substr($bundle, 0, -6);
        $parameters = [
            'namespace' => str_replace('Bundle', '', $namespace),
            'bundle' => $bundle,
            'bundle_basename' => $basename,
            'bundle_basename_lower' => strtolower($basename),
            'extension_alias' => Container::underscore($basename),
            'fieldtype_name' => $fieldTypeName,
            'fieldtype_basename' => self::identify($fieldTypeName),
            'fieldtype_identifier' => strtolower(self::identify($fieldTypeName)),
            'fieldtype_namespace' => $fieldTypeNamespace,
            'fieldtype_namespace_identifier' => strtolower(self::identify($fieldTypeNamespace)),
        ];

        $this->setSkeletonDirs([
            $this->kernel->locateResource('@EdgarEzFieldTypeExtraBundle/Resources/skeleton'),
        ]);

        $this->renderFile('fieldtype/src/bundle/Bundle.php.html.twig', $dir . '/src/bundle/' . $bundle . '.php', $parameters);
        $this->renderFile('fieldtype/src/bundle/DependencyInjection/Extension.php.html.twig', $dir . '/src/bundle/DependencyInjection/' . $basename . 'Extension.php', $parameters);
        $this->renderFile('fieldtype/src/lib/FieldType/SearchField.php.html.twig', $dir . '/src/lib/FieldType/' . self::identify($fieldTypeName) . '/SearchField.php', $parameters);
        $this->renderFile('fieldtype/src/lib/FieldType/Type.php.html.twig', $dir . '/src/lib/FieldType/' . self::identify($fieldTypeName) . '/Type.php', $parameters);
        $this->renderFile('fieldtype/src/lib/FieldType/Value.php.html.twig', $dir . '/src/lib/FieldType/' . self::identify($fieldTypeName) . '/Value.php', $parameters);
        $this->renderFile('fieldtype/src/lib/FieldType/Mapper/FormMapper.php.html.twig', $dir . '/src/lib/FieldType/Mapper/' . self::identify($fieldTypeName) . 'FormMapper.php', $parameters);
        $this->renderFile('fieldtype/src/lib/Persistence/Legacy/Content/FieldValue/Converter/Converter.php.html.twig', $dir . '/src/lib/Persistence/Legacy/Content/FieldValue/Converter/' . self::identify($fieldTypeName) . 'Converter.php', $parameters);
        $this->renderFile('fieldtype/src/lib/Form/Type/FieldType/FieldType.php.html.twig', $dir . '/src/lib/Form/Type/FieldType/' . self::identify($fieldTypeName) . 'FieldType.php', $parameters);

        $this->renderFile('fieldtype/src/bundle/Resources/config/field_templates.yml.html.twig', $dir . '/src/bundle/Resources/config/field_templates.yml', $parameters);
        $this->renderFile('fieldtype/src/bundle/Resources/config/field_value_converters.yml.html.twig', $dir . '/src/bundle/Resources/config/field_value_converters.yml', $parameters);
        $this->renderFile('fieldtype/src/bundle/Resources/config/fieldtypes.yml.html.twig', $dir . '/src/bundle/Resources/config/fieldtypes.yml', $parameters);
        $this->renderFile('fieldtype/src/bundle/Resources/config/indexable_fieldtypes.yml.html.twig', $dir . '/src/bundle/Resources/config/indexable_fieldtypes.yml', $parameters);
        $this->renderFile('fieldtype/src/bundle/Resources/views/content_fields.html.twig.html.twig', $dir . '/src/bundle/Resources/views/content_fields.html.twig', $parameters);
        $this->renderFile('fieldtype/src/bundle/Resources/views/field_types.html.twig.html.twig', $dir . '/src/bundle/Resources/views/field_types.html.twig', $parameters);
        $this->renderFile('fieldtype/src/bundle/Resources/views/fielddefinition_settings.html.twig.html.twig', $dir . '/src/bundle/Resources/views/fielddefinition_settings.html.twig', $parameters);

        $this->renderFile('fieldtype/src/bundle/Resources/translations/ezrepoforms_content_type.en.yml.html.twig', $dir . '/src/bundle/Resources/translations/ezrepoforms_content_type.en.yml', $parameters);
        $this->renderFile('fieldtype/src/bundle/Resources/translations/fieldtypes.en.yml.html.twig', $dir . '/src/bundle/Resources/translations/fieldtypes.en.yml', $parameters);
    }

    /**
     * Transform string to add underscore.
     *
     * @param string $id
     *
     * @return string
     */
    public static function underscore(string $id): string
    {
        return preg_replace(['/([A-Z]+)([A-Z][a-z])/', '/([a-z\d])([A-Z])/'], ['\\1_\\2', '\\1_\\2'], str_replace([' ', '_'], '', $id));
    }

    /**
     * @param string $fieldTypeName
     *
     * @return string
     */
    public static function identify(string $fieldTypeName): string
    {
        $fieldTypeName = self::underscore($fieldTypeName);
        $fieldTypeName = str_replace('_', '', $fieldTypeName);

        return $fieldTypeName;
    }
}
