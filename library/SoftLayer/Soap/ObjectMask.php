<?php
class SoftLayer_Soap_ObjectMask
{
    public function __get($var)
    {
        $this->{$var} = new SoftLayer_Soap_ObjectMask();

        return $this->{$var};
    }
}
