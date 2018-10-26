<?php

namespace Envms\Osseus\Database;

/**
 * Class Table
 */
class Table
{

    /** @var string - Matches the database component name which precedes $divider */
    protected $component = '';
    /** @var string - Splits the component and table name. Defaults to empty string */
    protected $divider = '';
    /** @var string - The main table name which is part of a component (if set) */
    protected $name = null;

    /** @var string */
    protected $fullName;

    /**
     * @param string $name
     * @param string $component
     * @param string $divider
     */
    public function __construct(string $name, string $component = '', string $divider = '')
    {
        $this->component = $component;
        $this->divider = $divider;
        $this->name = $name;

        $this->fullName = $this->component . $this->divider . $this->name;
    }

    /**
     * @param $property
     *
     * @return string
     */
    public function __get($property): string
    {
        return $this->$property;
    }

    /**
     * @param $component
     */
    public function setComponent(string $component): void
    {
        $this->component = $component;
        $this->fullName = $this->component . $this->divider . $this->name;
    }

    /**
     * @param $divider
     */
    public function setDivider(string $divider): void
    {
        $this->divider = $divider;
        $this->fullName = $this->component . $this->divider . $this->name;
    }

    /**
     * @param $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
        $this->fullName = $this->component . $this->divider . $this->name;
    }

}
