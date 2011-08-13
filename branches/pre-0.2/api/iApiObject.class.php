<?php
interface iApiObject
{
	public function Get($value = null);
	public function Post();
	public function Put($value);
	public function Delete($value);
}
?>