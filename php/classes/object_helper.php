<?php
/**
 * REQUIRES PHP 5.3
 * it needs 5.3 is because it works on private and protected properties as well
 * if you just want to check public properties you can make it work in < 5.3
 */
class Object_Helper 
{
	
	public function __construct()
	{
		
	}

	/**
	 * @abstract like in_array(), this helper searches an objects properties for a needle
	 * @param $object (object) - the object to be searched [haystack]
	 * @param $needle (mixed) - a string/int/array to find in the object [needle]
	 * @return BOOL
	 */
	public static function in_object($object, $needle) 
	{
		$refobj = new ReflectionObject($object);
		$refprops = $refobj->getProperties();
		foreach($refprops as $prop) 
		{
			$prop->setAccessible(TRUE);
			if (is_array($needle))
			{
				if(in_array($prop->getValue($object), $needle)) 
					return TRUE;
			}
			else
			{
				if($prop->getValue($object) === $needle)) 
					return TRUE;
			}
		}
		return FALSE;
	}

}