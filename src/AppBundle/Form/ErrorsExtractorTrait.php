<?php

namespace AppBundle\Form;

use Symfony\Component\Form\FormInterface;

trait ErrorsExtractorTrait
{
    /**
     * !Recursion!
     * Recursively extracts all form field errors and return array of sub-arrays with field+message format
     *
     * @param FormInterface $form
     * @return array
     */
    private function extractAllErrors(FormInterface $form)
    {
        if ($form->isValid()) {
            return [];
        }

        $extractedErrors = [];
        $errors = $form->getErrors();

        foreach ($errors as $error) {
            $extractedErrors[] = ['field' => $form->getName(), 'value' => $error->getMessage()];
        }

        if ($form->count()) {
            $children = $form->all();

            foreach ($children as $child) {
                $extractedErrors = array_merge($extractedErrors, $this->extractAllErrors($child));
            }
        }

        return $extractedErrors;
    }
}