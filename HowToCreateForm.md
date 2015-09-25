# Introduction #
The framework includes a library called _Waf\_Forms_, which can help you creating, outputting and processing forms. The primary object is an instance of the class Waf\_Form and it's basically a collection of 3 types of child objects:
  * Inputs: Classes are stored in _/application/libraries/waf\_forms/inputs_. These objects store the form values and generate HTML for different kinds of input fields.
  * Validators: Validators are in the folder _/application/libraries/waf\_forms/validators_ and serve as both server side validation and client side validation of the form inputs.
  * Actions: Validators are in the folder _/application/libraries/waf\_forms/actions_ and define different kind of actions that need to be performed after form has been submitted and validated.

# Details #
The entire process of generating and processing a form consists of the following pseudocode
```
// Define the form, with all its inputs, validators and actions

if (formsent)
{
   // Validate form and collect any errors from failing validations
   
   if (form_is_valid)
   {
       // Perform the form actions
   }
} else {
   // Define the values (ie. default values for new records, load data from MySQL to edit a record)
}
// Show any errors if the form was sent and was invalid

// Show the form, with all inputs and possibly any values
```

The last 2 comments are the only steps that output something. The other steps should be performed before any output is done.