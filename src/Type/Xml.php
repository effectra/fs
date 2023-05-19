<?php

declare(strict_types=1);

namespace Effectra\Fs\Type;

use DOMDocument;
use Effectra\Fs\File;
use SimpleXMLElement;

/**
 * Class Xml
 *
 * Provides utility methods for working with xml files.
 */
class Xml
{
    /**
     * Checks if a file is of XML type.
     *
     * @param string $file The path to the file.
     * @return bool True if the file is of XML type, false otherwise.
     */
    public function isXml($file)
    {
        return File::mimeType($file) == 'text/xml';
    }

    /**
     * Reads an XML file and returns the contents as a SimpleXMLElement object.
     *
     * @param string $file The path to the XML file.
     * @return SimpleXMLElement The XML content as a SimpleXMLElement object.
     */
    public function read(string $file): SimpleXMLElement
    {
        return new SimpleXMLElement(static::readAsString($file));
    }

    /**
     * Reads an XML file and returns the contents as a string.
     *
     * @param string $file The path to the XML file.
     * @return string The XML content as a string.
     */
    public function readAsString(string $file)
    {
        return File::getContent($file, null);
    }
    /**
     * Recursively builds XML elements from array or nested data structure.
     *
     * @param SimpleXMLElement $xml The XML element to append the data to.
     * @param mixed $data The data to be converted to XML elements.
     * @param string|null $key The optional key name for the XML element.
     */
    private function buildXml(SimpleXMLElement $xml, $data, ?string $key = null)
    {
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                // Handle numeric keys by appending 'item' to the parent key
                $currentKey = is_numeric($k) ? 'item' : $k;

                // If the key is specified, use it; otherwise, use the current key
                $elementKey = $key !== null ? $key : $currentKey;

                // Create a new child element for the current data
                $child = $xml->addChild($elementKey);

                // Recursively build XML elements for nested data
                $this->buildXml($child, $v, $currentKey);
            }
        } else {
            // If the key is specified, use it; otherwise, use 'value'
            $elementKey = $key !== null ? $key : 'value';

            // Add the data as a child element to the current XML element
            $xml->addChild($elementKey, htmlspecialchars((string)$data));
        }
    }

    /**
     * Creates an XML file with the provided data.
     *
     * @param string $path The path where the XML file will be created.
     * @param mixed $data The data to be written in the XML file.
     * @return void
     */
    public function create(string $path, $data)
    {
        // Create a new SimpleXMLElement object
        $xml = new SimpleXMLElement('<root></root>');

        // Call a helper function to recursively build the XML structure
        static::buildXml($xml, $data);

        // Format the XML with indentation and line breaks
        $formattedXml = $xml->asXML();

        // Save the formatted XML to the specified path
        File::put($path, $formattedXml);
    }

    /**
     * Recursively applies updates to XML elements.
     *
     * @param SimpleXMLElement $xml The XML element to apply updates to.
     * @param array $updates The updates to be applied to the XML element.
     */
    private static function applyUpdates(SimpleXMLElement $xml, array $updates)
    {
        foreach ($updates as $key => $value) {
            // If the update is an array, it means we need to recursively apply updates to nested elements
            if (is_array($value)) {
                // Check if the element with the given key exists
                if (isset($xml->{$key})) {
                    // Recursively apply updates to the nested element
                    static::applyUpdates($xml->{$key}, $value);
                }
            } else {
                // If the update is a scalar value, update the element's value
                if (isset($xml->{$key})) {
                    $xml->{$key} = $value;
                }
            }
        }
    }

    /**
     * Edit an XML file with the provided updates.
     *
     * @param string $file The path to the XML file to be edited.
     * @param array $updates The updates to be applied to the XML.
     * @return int|false Returns the number of bytes written to the file on success, or false on failure.
     */
    public function edit(string $file, array $updates): int|false
    {
        // Read the XML file and convert it to a SimpleXMLElement
        $xmlString = File::getContent($file);
        // Load the XML file and create a SimpleXMLElement
        $xml = new SimpleXMLElement($xmlString);

        // Apply the updates to the XML
        static::applyUpdates($xml, $updates);

        // Format the updated XML with indentation and line breaks
        // Format the updated XML with indentation and line breaks
        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());

        // Save the updated XML back to the file
        return $dom->save($file);
    }

    /**
     * Retrieves the root element name of an XML file.
     *
     * @param string $file The path to the XML file.
     * @return string The name of the root element.
     */
    public function getName(string $file)
    {
        $sxe = new SimpleXMLElement(static::readAsString($file));

        return $sxe->getName();
    }
}
