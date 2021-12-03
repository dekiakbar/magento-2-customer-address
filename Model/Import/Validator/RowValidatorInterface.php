<?php
namespace Deki\CustomerAddress\Model\Import\Validator;

interface RowValidatorInterface extends \Magento\Framework\Validator\ValidatorInterface
{
    const ERROR_TITLE_IS_EMPTY= 'InvalidValueTITLE';
    const ERROR_MESSAGE_IS_EMPTY = 'EmptyMessage';

    /**
     * Initialize validator
     *
     * @return $this
     */
    public function init($context);
}
