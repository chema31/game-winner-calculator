<?php

namespace GWC\Interfaces;

interface IFileAction
{
    public function __construct(IParser $parser, IView $view);
}
