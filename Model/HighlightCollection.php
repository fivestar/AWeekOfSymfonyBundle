<?php

namespace Bundle\AWeekOfSymfonyBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Highlight collection
 *
 * @author Katsuhiro Ogawa <ko.fivestar@gmail.com>
 */
class HighlightCollection extends ArrayCollection
{
    protected $label;

    public function setLabel($label)
    {
        $this->label = $label;
    }

    public function getLabel()
    {
        return $this->label;
    }
}
