<?php

namespace Tests\ApiBundle\Form;

use AppBundle\Form\ErrorsExtractorTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;

class ErrorsExtractorTraitTest extends TestCase
{
    use ErrorsExtractorTrait;

    protected function getFormMock($errors = [], $name = 'test', $withChildren = [])
    {
        $form = $this->createMock(Form::class);

        $isValid = count($errors) <= 0;

        if ($isValid && count($withChildren)) {
            foreach ($withChildren as $child) {
                if (!$child->isValid()) {
                    $isValid = false;
                    break;
                }
            }
        }

        $form->method('isValid')
            ->willReturn($isValid);

        $form->method('getName')
            ->willReturn($name);
        $form->method('getErrors')
            ->willReturn($errors);
        $form->method('count')
            ->willReturn(count($withChildren));
        $form->method('all')
            ->willReturn($withChildren);

        return $form;
    }

    /**
     * @dataProvider getPositiveData
     */
    public function testExtractAllErrorsPositive($form, $result)
    {
        $this->assertEquals($result, $this->extractAllErrors($form));
    }

    /**
     * @dataProvider getNegativeData
     */
    public function testExtractAllErrorsNegative($form, $exception)
    {
        $this->expectException($exception);

        $this->extractAllErrors($form);
    }


    public function getPositiveData()
    {
        $form1 = $this->getFormMock([]);
        $form2 = $this->getFormMock([new FormError('test1_value'), new FormError('test2_value')]);

        $form3Child1 = $this->getFormMock([new FormError('test_child1_value')], 'test_child1');
        $form3Child2 = $this->getFormMock([new FormError('test_child2_value')], 'test_child2');
        $form3 = $this->getFormMock([new FormError('test1_value')], 'test', [$form3Child1, $form3Child2]);

        return [
            [$form1, []],
            [$form2, [
                ['field' => 'test', 'value' => 'test1_value'],
                ['field' => 'test', 'value' => 'test2_value']
            ]],
            [$form3, [
                ['field' => 'test', 'value' => 'test1_value'],
                ['field' => 'test_child1', 'value' => 'test_child1_value'],
                ['field' => 'test_child2', 'value' => 'test_child2_value']
            ]]
        ];
    }

    public function getNegativeData()
    {
        return [
            ['111', \TypeError::class],
            [111, \TypeError::class],
            [111.11, \TypeError::class],
            [new \StdClass, \TypeError::class],
            [null, \TypeError::class]
        ];
    }
}