<?php

namespace Tests\Chaplean\Bundle\UserBundle\Validator\Constraints;

use Chaplean\Bundle\UnitBundle\Test\FunctionalTestCase;
use Chaplean\Bundle\UserBundle\Validator\Constraints\MinimalPasswordRequirements;

/**
 * Class MinimalPasswordRequirementsValidator.
 *
 * @package   Chaplean\Bundle\UserBundle\Tests\Validator\Constraints
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coopn.coop)
 */
class MinimalPasswordRequirementsValidatorTest extends FunctionalTestCase
{
    /**
     * @covers \Chaplean\Bundle\UserBundle\Validator\Constraints\MinimalPasswordRequirementsValidator::validate()
     *
     * @return void
     * @throws \Exception
     */
    public function testValidateTooShort()
    {
        $violations = $this->getContainer()->get('validator')->validate('12345', [new MinimalPasswordRequirements()]);

        $this->assertEquals(1, $violations->count());
        $this->assertContains('too_short', $violations->get(0)->getMessage());
    }

    /**
     * @covers \Chaplean\Bundle\UserBundle\Validator\Constraints\MinimalPasswordRequirementsValidator::validate()
     *
     * @return void
     * @throws \Exception
     */
    public function testValidateTooShortWithCustomLength()
    {
        $violations = $this->getContainer()->get('validator')->validate('sdsdsd', [new MinimalPasswordRequirements(['minLength' => 7])]);

        $this->assertEquals(1, $violations->count());
    }

    /**
     * @covers \Chaplean\Bundle\UserBundle\Validator\Constraints\MinimalPasswordRequirementsValidator::validate()
     *
     * @return void
     * @throws \Exception
     */
    public function testValidateSpecialCharacter()
    {
        $violations = $this->getContainer()->get('validator')->validate('sdsdsd', [new MinimalPasswordRequirements(['minLength' => 0, 'atLeastOneSpecialCharacter' => true])]);

        $this->assertEquals(1, $violations->count());
        $this->assertContains('special_character', $violations->get(0)->getMessage());
    }

    /**
     * @covers \Chaplean\Bundle\UserBundle\Validator\Constraints\MinimalPasswordRequirementsValidator::validate()
     *
     * @return void
     * @throws \Exception
     */
    public function testValidateSpecialCharacterFalse()
    {
        $violations = $this->getContainer()->get('validator')->validate('sdsdsd', [new MinimalPasswordRequirements(['minLength' => 0, 'atLeastOneSpecialCharacter' => false])]);

        $this->assertEquals(0, $violations->count());
    }

    /**
     * @covers \Chaplean\Bundle\UserBundle\Validator\Constraints\MinimalPasswordRequirementsValidator::validate()
     *
     * @return void
     * @throws \Exception
     */
    public function testValidateSpecialCharacterWithSpecialCharacter()
    {
        $violations = $this->getContainer()->get('validator')->validate('sdsdsd@@', [new MinimalPasswordRequirements()]);

        $this->assertEquals(0, $violations->count());
    }
}
