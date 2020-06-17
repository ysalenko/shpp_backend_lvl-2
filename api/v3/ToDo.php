<?php

class ToDo
{
    public int $id;
    public string $text;
    public bool $checked;

    public function __construct($id = 0, $text = '', $checked = false)
    {
        $this->id = (int)$id;
        $this->text = $text;
        $this->checked = (bool)$checked;
    }
}