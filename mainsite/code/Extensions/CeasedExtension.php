<?php
class CeasedExtension extends DataExtension
{
    /**
     * Database fields
     * @var array
     */
    private static $db = array(
        'Ceased'    =>  'Boolean'
    );

    /**
     * Update Fields
     * @return FieldList
     */
    public function updateCMSFields(FieldList $fields)
    {
        $owner = $this->owner;
        $fields->addFieldToTab(
            'Root.Main',
            CheckboxField::create(
                'Ceased',
                'Is this item/information ceased or expired?'
            ),
            'Title'
        );
        return $fields;
    }
}
