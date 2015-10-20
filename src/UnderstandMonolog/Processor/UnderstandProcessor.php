<?php namespace UnderstandMonolog\Processor;

class UnderstandProcessor
{

    /**
     * Field providers
     *
     * @var array
     */
    protected $fieldProviders;

    /**
     * @param array $fieldProviders
     */
    public function __construct(array $fieldProviders = [])
    {
        $this->fieldProviders = $fieldProviders;
    }

    /**
     * Adds additional data
     *
     * @param array $record
     * @return array
     */
    public function __invoke(array $record)
    {
        foreach($this->fieldProviders as $fieldName => $providerOrValue)
        {
            if (is_callable($providerOrValue))
            {
                $record[$fieldName] = call_user_func($providerOrValue, $record);
            }
            else
            {
                $record[$fieldName] = $providerOrValue;
            }
        }

        return $record;
    }
}
