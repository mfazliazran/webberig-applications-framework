<?php
	class api_users implements iApiObject
	{
		public function Get($value = null)
		{
			if ($value)
			{
				$obj = new ApiReturnObject();
				$obj->data = Users::GetUserByID($value);
				$obj->error = "";
				$obj->status = 200;
				return $obj;
			} else {
				$obj = new ApiReturnObject();
				$obj->data = ApiHelper::ReadRecords(Users::GetList());
				$obj->error = "";
				$obj->status = 200;
				return $obj;
			}
		}
		public function Post()
		{
			
		}
		public function Put($value)
		{
			
		}
		public function Delete($value)
		{
			
		}
		
	}
?>