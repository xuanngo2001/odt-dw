<?php
/**
 * ODTParagraphStyle: class for ODT paragraph styles.
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author LarsDW223
 */

require_once DOKU_INC.'lib/plugins/odt/ODT/XMLUtil.php';
require_once DOKU_INC.'lib/plugins/odt/ODT/styles/ODTStyleStyle.php';
require_once DOKU_INC.'lib/plugins/odt/ODT/styles/ODTTextStyle.php';

/**
 * The ODTParagraphStyle class
 */
class ODTParagraphStyle extends ODTStyleStyle
{
    static $paragraph_fields = array(
        'line-height'                      => array ('fo:line-height',                      'paragraph',  true),
        'line-height-at-least'             => array ('style:line-height-at-least',          'paragraph',  true),
        'line-spacing'                     => array ('style:line-spacing',                  'paragraph',  true),
        'font-independent-line-spacing'    => array ('style:font-independent-line-spacing', 'paragraph',  true),
        'text-align'                       => array ('fo:text-align',                       'paragraph',  true),
        'text-align-last'                  => array ('fo:text-align-last',                  'paragraph',  true),
        'justify-single-word'              => array ('style:justify-single-word',           'paragraph',  true),
        'keep-together'                    => array ('fo:keep-together',                    'paragraph',  true),
        'widows'                           => array ('fo:widows',                           'paragraph',  true),
        'orphans'                          => array ('fo:orphans',                          'paragraph',  true),
        'tab-stop-distance'                => array ('style:tab-stop-distance',             'paragraph',  true),
        'hyphenation-keep'                 => array ('fo:hyphenation-keep',                 'paragraph',  true),
        'hyphenation-ladder-count'         => array ('fo:hyphenation-ladder-count',         'paragraph',  true),
        'register-true'                    => array ('style:register-true',                 'paragraph',  true),
        'text-indent'                      => array ('fo:text-indent',                      'paragraph',  true),
        'auto-text-indent'                 => array ('style:auto-text-indent',              'paragraph',  true),
        'margin'                           => array ('fo:margin',                           'paragraph',  true),
        'margin-top'                       => array ('fo:margin-top',                       'paragraph',  true),
        'margin-right'                     => array ('fo:margin-right',                     'paragraph',  true),
        'margin-bottom'                    => array ('fo:margin-bottom',                    'paragraph',  true),
        'margin-left'                      => array ('fo:margin-left',                     'paragraph',  true),
        'break-before'                     => array ('fo:break-before',                     'paragraph',  true),
        'break-after'                      => array ('fo:break-after',                      'paragraph',  true),
        'background-color'                 => array ('fo:background-color',                 'paragraph',  true),
        'border'                           => array ('fo:border',                           'paragraph',  true),
        'border-top'                       => array ('fo:border-top',                        'paragraph',  true),
        'border-right'                     => array ('fo:border-right',                      'paragraph',  true),
        'border-bottom'                    => array ('fo:border-bottom',                     'paragraph',  true),
        'border-left'                      => array ('fo:border-left',                       'paragraph',  true),
        'border-line-width'                => array ('style:border-line-width',              'paragraph',  true),
        'border-line-width-top'            => array ('style:border-line-width-top',          'paragraph',  true),
        'border-line-width-bottom'         => array ('style:border-line-width-bottom',       'paragraph',  true),
        'border-line-width-left'           => array ('style:border-line-width-left',         'paragraph',  true),
        'border-line-width-right'          => array ('style:border-line-width-right',        'paragraph',  true),
        'join-border'                      => array ('style:join-border',                    'paragraph',  true),
        'padding'                          => array ('fo:padding',                           'paragraph',  true),
        'padding-top'                      => array ('fo:padding-top',                       'paragraph',  true),
        'padding-bottom'                   => array ('fo:padding-bottom',                    'paragraph',  true),
        'padding-left'                     => array ('fo:padding-left',                      'paragraph',  true),
        'padding-right'                    => array ('fo:padding-right',                     'paragraph',  true),
        'shadow'                           => array ('style:shadow',                         'paragraph',  true),
        'keep-with-next'                   => array ('fo:keep-with-next',                    'paragraph',  true),
        'number-lines'                     => array ('text:number-lines',                    'paragraph',  true),
        'line-number'                      => array ('text:line-number',                     'paragraph',  true),
        'text-autospace'                   => array ('style:text-autospace',                 'paragraph',  true),
        'punctuation-wrap'                 => array ('style:punctuation-wrap',               'paragraph',  true),
        'line-break'                       => array ('style:line-break',                     'paragraph',  true),
        'vertical-align'                   => array ('style:vertical-align',                 'paragraph',  true),
        'writing-mode'                     => array ('style:writing-mode',                   'paragraph',  true),
        'writing-mode-automatic'           => array ('style:writing-mode-automatic',         'paragraph',  true),
        'snap-to-layout-grid'              => array ('style:snap-to-layout-grid',            'paragraph',  true),
        'page-number'                      => array ('style:page-number',                    'paragraph',  true),
        'background-transparency'          => array ('style:background-transparency',        'paragraph',  true),
    );

    // Additional fields for child element tab-stop.
    static $tab_stop_fields = array(
        'style-position'                   => array ('style:position',                       'tab-stop',   true),
        'style-type'                       => array ('style:type',                           'tab-stop',   true),
        'style-leader-type'                => array ('style:leader-type',                    'tab-stop',   true),
        'style-leader-style'               => array ('style:leader-style',                   'tab-stop',   true),
        'style-leader-width'               => array ('style:leader-width',                   'tab-stop',   true),
        'style-leader-color'               => array ('style:leader-color',                   'tab-stop',   true),
        'style-leader-text'                => array ('style:leader-text',                    'tab-stop',   true),
    );

    protected $style_properties = array();
    protected $text_properties = array();
    protected $tab_stops = array();

    /**
     * Set style properties by importing values from a properties array.
     * Properties might be disabled by setting them in $disabled.
     * The style must have been previously created.
     *
     * @param  $properties Properties to be imported
     * @param  $disabled Properties to be ignored
     */
    public function importProperties($properties, $disabled) {
        foreach ($properties as $property => $value) {
            $this->setProperty($property, $value);
        }
    }

    /**
     * Check if a style is a common style.
     *
     * @return bool Is common style
     */
    public function mustBeCommonStyle() {
        return false;
    }

    /**
     * Get the style family of a style.
     *
     * @return string Style family
     */
    public function getFamily() {
        return 'paragraph';
    }

    /**
     * Set a property.
     * 
     * @param $property The name of the property to set
     * @param $value    New value to set
     */
    public function setProperty($property, $value) {
        $style_fields = ODTStyleStyle::getStyleProperties ();
        if (array_key_exists ($property, $style_fields)) {
            $this->setPropertyInternal
                ($property, $style_fields [$property][0], $value, $style_fields [$property][1], $this->style_properties);
            return;
        }
        // Compare with paragraph fields before text fields first!
        // So, paragraph properties get precedence.
        if (array_key_exists ($property, self::$paragraph_fields)) {
            $this->setPropertyInternal
                ($property, self::$paragraph_fields [$property][0], $value, self::$paragraph_fields [$property][1]);
            return;
        }
        $text_fields = ODTTextStyle::getTextProperties ();
        if (array_key_exists ($property, $text_fields)) {
            $this->setPropertyInternal
                ($property, $text_fields [$property][0], $value, $text_fields [$property][1], $this->text_properties);
            return;
        }
    }

    /**
     * Get the value of a property.
     * 
     * @param  $property The property name
     * @return string The current value of the property
     */
    public function getProperty($property) {
        $style_fields = ODTStyleStyle::getStyleProperties ();
        if (array_key_exists ($property, $style_fields)) {
            return $this->style_properties [$property]['value'];
        }
        $text_fields = ODTTextStyle::getTextProperties ();
        if (array_key_exists ($property, $text_fields)) {
            return $this->text_properties [$property]['value'];
        }
        return parent::getProperty($property);
    }

    /**
     * Create new style by importing ODT style definition.
     *
     * @param  $xmlCode Style definition in ODT XML format
     * @return ODTStyle New specific style
     */
    static public function importODTStyle($xmlCode) {
        $style = new ODTParagraphStyle();
        $attrs = 0;

        $open = XMLUtil::getElementOpenTag('style:style', $xmlCode);
        if (!empty($open)) {
            $attrs += $style->importODTStyleInternal(ODTStyleStyle::getStyleProperties (), $open, $style->style_properties);
        } else {
            $open = XMLUtil::getElementOpenTag('style:default-style', $xmlCode);
            if (!empty($open)) {
                $style->setDefault(true);
                $attrs += $style->importODTStyleInternal(ODTStyleStyle::getStyleProperties (), $open, $style->style_properties);
            }
        }

        $open = XMLUtil::getElementOpenTag('style:paragraph-properties', $xmlCode);
        if (!empty($open)) {
            $attrs += $style->importODTStyleInternal(self::$paragraph_fields, $xmlCode);
        }

        $open = XMLUtil::getElementOpenTag('style:text-properties', $xmlCode);
        if (!empty($open)) {
            $attrs += $style->importODTStyleInternal(ODTTextStyle::getTextProperties (), $open, $style->text_properties);
        }

        // Get all tab-stops.
        $tabs = XMLUtil::getElementContent('style:tab-stops', $xmlCode);
        if ($tabs != NULL) {
            $max = strlen($tabs);
            $pos = 0;
            $index = 0;
            $tab = XMLUtil::getElement('style:tab-stop', $tabs, $end);
            $pos = $end;
            while ($tab != NULL) {
                $style->tab_stops [$index] = array();
                $attrs += $style->importODTStyleInternal(self::$tab_stop_fields, $tab, $style->tab_stops [$index]);
                $index++;
                $tab = XMLUtil::getElement('style:tab-stop', substr ($tabs, $pos), $end);
                $pos += $end;
            }
        }

        // If style has no meaningfull content then throw it away
        if ( $attrs == 0 ) {
            return NULL;
        }

        return $style;
    }

    static public function getParagraphProperties () {
        return self::$paragraph_fields;
    }

    /**
     * Encode current style values in a string and return it.
     *
     * @return string ODT XML encoded style
     */
    public function toString() {
        $style = '';
        $para_props = '';
        $text_props = '';
        $tab_stops_props = '';

        // Get style contents
        foreach ($this->style_properties as $property => $items) {
            $style .= $items ['odt_property'].'="'.$items ['value'].'" ';
        }

        // Get paragraph properties ODT properties
        foreach ($this->properties as $property => $items) {
            $para_props .= $items ['odt_property'].'="'.$items ['value'].'" ';
        }

        // Get text properties
        foreach ($this->text_properties as $property => $items) {
            $text_props .= $items ['odt_property'].'="'.$items ['value'].'" ';
        }

        // Get tab-stops properties
        for ($index = 0 ; $index < count($this->tab_stops) ; $index++) {
            $tab_stops_props .= '<style:tab-stop ';
            foreach ($this->tab_stops[$index] as $property => $items) {
                $tab_stops_props .= $items ['odt_property'].'="'.$items ['value'].'" ';
            }
            $tab_stops_props .= '/>';
        }

        // Build style.
        if (!$this->isDefault()) {
            $style  = '<style:style '.$style.">\n";
        } else {
            $style  = '<style:default-style '.$style.">\n";
        }
        if (!empty($para_props) || !empty($tab_stops_props)) {
            if (empty($tab_stops_props)) {
                $style .= '<style:paragraph-properties '.$para_props."/>\n";
            } else {
                $style .= '<style:paragraph-properties '.$para_props.">\n";
                $style .= '<style:tab-stops>'."\n";
                $style .= $tab_stops_props."\n";
                $style .= '</style:tab-stops>'."\n";
                $style .= '</style:paragraph-properties>'."\n";
            }
        }
        if (!empty($text_props)) {
            $style .= '<style:text-properties '.$text_props."/>\n";
        }
        if (!$this->isDefault()) {
            $style  .= '</style:style>'."\n";
        } else {
            $style  .= '</style:default-style>'."\n";
        }
        return $style;
    }
}

