<?php
 
namespace AppBundle\Tests\Feature\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;

class FeatureContext extends MinkContext implements Context, SnippetAcceptingContext
{
    /**
     * @When I click on the first link in :element
     */
    public function iClickOnTheFirstLinkIn($element)
    {
        $link = $this->getSession()->getPage()->find('css', sprintf("%s li a", $element));

        $link->click();
    }
}
