<?php
class Waf_Form
{
	// Arrays of all objects
	public $inputs 	= array();
	public $validators = array();
	public $actions 	= array();
	
	// form parameters
	public $buttonLabel;
	public $confirmation;
	private $errors = array();
	public $isValid = false;
	public $id;
	
	public function __construct($id = "")
	{
		$this->id = $id;
        $logger = Logger::getLogger("ApplicationCore.Libraries.Waf_Forms");
        $logger->debug("Initializing form " . $id);
	}
	
    public function isSent()
    {
        return (count($_POST));
    }
	// Output any errors if any
	public function ShowErrors()
	{
            /*
		if (count($_POST) && count($this->errors))
		{
		
?>
	<div class="formerrors">
		<ul>
<?php
				foreach($this->errors as $error)
				{
?>
			<li><span><?php echo $error;?></span></li>
<?php
				}
?>
		</ul>
	</div>
<?php
		}
           */
	}
	
	public function AddError($msg)
	{
		$this->errors[] = $msg;
		$this->isValid = false;
	}
	
    public function LoadXml($url)
    {
        $logger = Logger::getLogger("ApplicationCore.Libraries.Waf_Forms");
        $logger->debug("Form " . $this->id . ": Reading from XML " . $url);
        $xml = simplexml_load_file($url);
        foreach ($xml->input as $input)
        {
            if (!isset($input->attributes()->type))
            {
                throw new Exception("Input has no type");
                return;
            }
            if (!isset($input->attributes()->name))
            {
                throw new Exception("Input has no name");
                return;
            }
            $type = (string) $input->attributes()->type;
            $name = (string) $input->attributes()->name;

            $objInput = $this->CreateInput($type, $name);

            foreach($input->param as $param)
            {
                if (!isset($param->attributes()->name))
                {
                    throw new Exception("Parameter without a name");
                    return;
                }
                
                $paramname = (string) $param->attributes()->name;
                $value = (string) $param;
                $objInput->$paramname = $value;
            }
        }
        foreach ($xml->action as $action)
        {
            if (!isset($action->attributes()->type))
            {
                throw new Exception("Action has no type");
                return;
            }
            $type = (string) $action->attributes()->type;
            $objAction = $this->CreateAction($type);

            foreach($action->param as $param)
            {
                if (!isset($param->attributes()->name))
                {
                    throw new Exception("Parameter without a name");
                    return;
                }
                
                $paramname = (string) $param->attributes()->name;
                $value = (string) $param;
                $objAction->$paramname = $value;
            }
        }
        foreach ($xml->validator as $validator)
        {
            if (!isset($validator->attributes()->type))
            {
                throw new Exception("Validator has no type");
                return;
            }
            if (!isset($validator->attributes()->field))
            {
                throw new Exception("Validator has no field");
                return;
            }
            $type = (string) $validator->attributes()->type;
            $field = (string) $validator->attributes()->field;

            $objValidator = $this->CreateValidator($type, $field);

            foreach($validator->param as $param)
            {
                if (!isset($param->attributes()->name))
                {
                    throw new Exception("Parameter without a name");
                    return;
                }
                
                $paramname = (string) $param->attributes()->name;
                $value = (string) $param;
                $objValidator->$paramname = $value;
            }
        }
    }
    
	// Process the $_POST fields if any
	public function ProcessForm()
	{
        $logger = Logger::getLogger("ApplicationCore.Libraries.Waf_Forms");
        $logger->debug("Form " . $this->id . ": Processing form");
		if ($this->isSent())
		{
            
			// set the input values
			foreach($this->inputs as $input)
			{
				if (isset($_POST[$input->name]))
				{
					$input->value = $_POST[$input->name];
				} else {
					$input->value = "";
				}
			}
			
			// Validate
			$this->Validate();
			
			if ($this->isValid)
			{
				// Perform actions
				foreach($this->actions as $action)
				{
					$action->Execute();
				}
				if (strlen($this->confirmation))
                                {
                                    Messenger::Add("confirm", $this->confirmation);
                                }
			}
		} 
	}
	
	// Validate the form and return all errors
	private function Validate()
	{
        $logger = Logger::getLogger("ApplicationCore.Libraries.Waf_Forms");
        $logger->debug("Form " . $this->id . ": Validating");
		$this->errors = array();
		foreach($this->validators as $validator)
		{
			if (!$validator->Validate())
			{
                            $this->errors[] = $validator->message;
                            Messenger::Add("error", $validator->message);
                        }
		}
		if (count($this->errors) == 0)
		{
			$logger->debug("Form " . $this->id . ": Validation SUCCESS");
			$this->isValid = true;
		} else {
			$logger->debug("Form " . $this->id . ": Validation FAILED");
			$this->isValid = false;
		}
	}
    
	public function SetValues($arr)
	{
        $logger = Logger::getLogger("ApplicationCore.Libraries.Waf_Forms");
        $logger->debug("Form " . $this->id . ": Setting values");
		foreach ($arr as $key => $value)
		{
			if (isset($this->inputs[$key]))
			{
				$this->inputs[$key]->value = $value;
			}
		}
	}
	// Show the entire form
	public function ShowForm()
	{
        $logger = Logger::getLogger("ApplicationCore.Libraries.Waf_Forms");
        $logger->debug("Form " . $this->id . ": Showing form");
		if (!$this->ShowingForm())
		{
			$this->ShowComfirmation();
			return;
		}
?>
	<form method="post">
<?php
		foreach ($this->inputs as $input)
		{
			$input->Output();
		}
?>
		<div class="buttons">
        	<input type="submit" value="<?php echo $this->buttonLabel;?>"/>
        </div>
	</form>
<?php
	}
	
	public function Show($name)
	{
        $logger = Logger::getLogger("ApplicationCore.Libraries.Waf_Forms");
        $logger->debug("Form " . $this->id . ": Outputting '". $name ."'");
		if (isset($this->inputs[$name]))
		{
			$this->inputs[$name]->Output();
		}
	}
   
	// Object factories
	public function CreateInput($class, $name)
	{
        $logger = Logger::getLogger("ApplicationCore.Libraries.Waf_Forms");
        $logger->debug("Form " . $this->id . ": Creating '". $class ."' input '". $name ."'");
		$className = "Waf_Input_" . $class;
		require_once("application/libraries/waf_forms/inputs/".$className.".class.php");
		
		$input = new $className($name);
		$this->inputs[$name] = $input;
		
		return $input;
	}
    public function CreateValidator($class, $field)
    {
        $logger = Logger::getLogger("ApplicationCore.Libraries.Waf_Forms");
        $logger->debug("Form " . $this->id . ": Creating '". $class ."' validator for '". $field ."'");
        $className = "Waf_Validator_" . $class;
        require_once("application/libraries/waf_forms/validators/".$className.".class.php");

        $validator = new $className($this, $field);
        $this->validators[] = $validator;
        return $validator;
    }
    public function CreateAction($class)
    {
        $logger = Logger::getLogger("ApplicationCore.Libraries.Waf_Forms");
        $logger->debug("Form " . $this->id . ": Creating '". $class ."' action");
        $className = "Waf_Action_" . $class;
        require_once("application/libraries/waf_forms/actions/".$className.".class.php");

        $action = new $className($this);
        $this->actions[] = $action;
        return $action;
    }
        
    public function ToArray()
    {
            $arr = array();
            foreach ($this->inputs as $input)
            {
                    $arr[$input->name] = $input->value;
            }
            return $arr;
    }

}
?>