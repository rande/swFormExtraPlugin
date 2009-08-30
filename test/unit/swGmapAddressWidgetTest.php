<?php

include(dirname(__FILE__).'/../bootstrap/unit.php');

$t = new lime_test(9, new lime_output_color());


class NestedMapForm extends sfForm
{
  public function configure()
  {
    $this->widgetSchema['nested_map']    = new swWidgetFormGMapAddress;
    $this->validatorSchema['nested_map'] = new swValidatorGMapAddress;
  }
}

class PrimaryForm extends sfForm
{
  public function configure()
  {
    $this->widgetSchema->setNameFormat('primary_form[%s]');
    
    $this->widgetSchema['map']    = new swWidgetFormGMapAddress;
    $this->validatorSchema['map'] = new swValidatorGMapAddress;
    
    $this->embedForm('nested_form', new NestedMapForm);
  }
  
  public function save()
  {
    $address = $this->getValue('map');
    
    $this->myObject->setAddress($address['address']);
    $this->myObject->setLng($address['lng']);
    $this->myObject->setLat($address['lat']);
  }
}

$form = new PrimaryForm;

$result =<<<FORM
<tr>
  <th>Map</th>
  <td>
      <div id="primary_form_map" class="sw-gmap-widget">
        <label for="primary_form_map_address">Address</label>
  <input style="width: 300px;" type="text" name="primary_form[map][address]" id="primary_form_map_address" />

 <input type="submit" value="lookup address"  id="primary_form_map_lookup" /> <br />
       <label for="primary_form_map_lat">Lat</label>
  <input readonly="1" type="text" name="primary_form[map][lat]" id="primary_form_map_lat" />

 - <label for="primary_form_map_lng">Lng</label>
  <input readonly="1" type="text" name="primary_form[map][lng]" id="primary_form_map_lng" />

 <br /> 
         <div id="primary_form_map_map" style="width: 500px; height: 300px"></div>
       </div>
      <script>
        jQuery(window).bind("load", function() {
          new swGmapWidget({ 
            lng: "primary_form_map_lng", 
            lat: "primary_form_map_lat", 
            address: "primary_form_map_address", 
            lookup: "primary_form_map_lookup", 
            map: "primary_form_map_map" 
          });
        })
      </script>
    </td>
</tr>
<tr>
  <th>Nested form</th>
  <td><table>
  <tr>
  <th>Nested map</th>
  <td>
      <div id="primary_form_nested_form_nested_map" class="sw-gmap-widget">
        <label for="primary_form_nested_form_nested_map_address">Address</label>
  <input style="width: 300px;" type="text" name="primary_form[nested_form][nested_map][address]" id="primary_form_nested_form_nested_map_address" />

 <input type="submit" value="lookup address"  id="primary_form_nested_form_nested_map_lookup" /> <br />
       <label for="primary_form_nested_form_nested_map_lat">Lat</label>
  <input readonly="1" type="text" name="primary_form[nested_form][nested_map][lat]" id="primary_form_nested_form_nested_map_lat" />

 - <label for="primary_form_nested_form_nested_map_lng">Lng</label>
  <input readonly="1" type="text" name="primary_form[nested_form][nested_map][lng]" id="primary_form_nested_form_nested_map_lng" />

 <br /> 
         <div id="primary_form_nested_form_nested_map_map" style="width: 500px; height: 300px"></div>
       </div>
      <script>
        jQuery(window).bind("load", function() {
          new swGmapWidget({ 
            lng: "primary_form_nested_form_nested_map_lng", 
            lat: "primary_form_nested_form_nested_map_lat", 
            address: "primary_form_nested_form_nested_map_address", 
            lookup: "primary_form_nested_form_nested_map_lookup", 
            map: "primary_form_nested_form_nested_map_map" 
          });
        })
      </script>
    </td>
</tr>
</table></td>
</tr>

FORM;

$t->cmp_ok($result, '==', $form->render(), 'html output is ok');


$params = array(
  'map' => array('lat' => 39, 'lng' =>  40),
  'nested_form' => array('map' => array('lat' => 200, 'lng' =>  200),)
);

$form->bind($params);

$errors = $form->getErrorSchema()->getErrors();

$t->ok(isset($errors['map']['address']), 'address error presents');
$t->ok(!isset($errors['map']['lat']), 'lat error not presents');
$t->ok(!isset($errors['map']['lng']), 'lng error not presents');

$t->isa_ok($errors['nested_form'], 'sfValidatorErrorSchema', 'nested error instanceof sfValidatorErrorSchema');
$nested_errors = $errors['nested_form']->getErrors();

$t->ok(isset($nested_errors['nested_map']['address']), 'address error presents');
$t->ok(isset($nested_errors['nested_map']['lat']), 'lat error presents');
$t->ok(isset($nested_errors['nested_map']['lng']), 'lng error presents');


$params = array(
  'map' => array('lat' => 39, 'lng' =>  40, 'address' => 'la cantine, 151 rue montmatre, paris'),
  'nested_form' => array('nested_map' => array('lat' => 39, 'lng' =>  40, 'address' => 'la cantine, 151 rue montmatre, paris'),)
);

$form->bind($params);
$t->cmp_ok(count($form->getErrorSchema()->getErrors()), '==', 0, 'no error form is valid');
