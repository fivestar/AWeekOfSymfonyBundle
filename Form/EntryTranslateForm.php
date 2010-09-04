<?php

namespace Bundle\AWeekOfSymfonyBundle\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FieldGroup;
use Symfony\Component\Form\TextField;
use Symfony\Component\Form\TextareaField;

class EntryTranslateForm extends Form
{
    public function configure()
    {
        $entry = $this->getData();

        $this->add(new TextareaField('summary'));

        $highlightGroup = new FieldGroup('allHighlights');
        foreach ($entry->getAllHighlights() as $name => $highlights) {
            $name = preg_replace('/\W/', '', $name);
            $group = new FieldGroup($name);
            foreach ($highlights as $commit => $highlight) {
                $group->add(new TextField($commit));
            }

            $highlightGroup->add($group);
        }
        $this->add($highlightGroup);

        $this->add(new TextareaField('translator', array('property_path' => null)));
    }
}
