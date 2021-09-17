<?php
/**
 * IFileAction implementation for secuential parsing into a directory
 */

namespace GWC\Actions;

use GWC\Interfaces\IFileAction;
use GWC\Interfaces\IParser;
use GWC\Interfaces\IView;

class LookForWinners implements IFileAction
{
    private $parser;
    private $view;

    public function __construct(IParser $parser, IView $view)
    {
        $this->parser = $parser;
        $this->view = $view;
    }

    /**
     * Execute the tournament logic
     */
    public function execute()
    {
        $inputDir = scandir(INPUT_DIR);

        //Loop over all files
        foreach ($inputDir as $gameFile) {
            if (!in_array($gameFile, FILE_EXCEPTIONS)) { //Skip navigation directories
                $numParsedItems = $this->parser->parseFile($gameFile);
            }
        }

        if ($numParsedItems) {
            $this->view->render();

        } else {
            //TODO: Return an validation alert
        }
    }
}