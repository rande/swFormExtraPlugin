/*
 *  $Id$
 *
 * (c) 2009 Thomas Rabaix <thomas.rabaix@soleoweb.com>
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.soleoweb.com>.
 */
 
function swGmapWidget(options){
  // this global attributes  
  this.lng      = null;
  this.lat      = null;
  this.address  = null;
  this.map      = null;
  this.geocoder = null;
  this.options  = options;
  
  this.init();
}

swGmapWidget.prototype = new Object();

swGmapWidget.prototype.init = function() {
  
  if(!GBrowserIsCompatible()) 
  {
    return;
  }
  
  // retrieve dom element
  this.lng      = jQuery("#" + this.options.lng);
  this.lat      = jQuery("#" + this.options.lat);
  this.address  = jQuery("#" + this.options.address);
  this.lookup   = jQuery("#" + this.options.lookup);
  
  // create the google geocoder object
  this.geocoder = new GClientGeocoder();
  
  // create the map
  this.map = new GMap2(jQuery("#" + this.options.map).get(0));
  this.map.setCenter(new GLatLng(this.lat.val(), this.lng.val()), 13);
  this.map.setUIToDefault();
  
  // cross reference object
  this.map.swGmapWidget = this;
  this.geocoder.swGmapWidget = this;
  this.lookup.get(0).swGmapWidget = this;
  
  // add the default location
  var point = new GLatLng(this.lat.val(), this.lng.val());
  var marker = new GMarker(point);
  this.map.setCenter(point, 15);
  this.map.addOverlay(marker);
  
  // bind the move action on the map
  GEvent.addListener(this.map, "move", function() {
     var center = this.getCenter();
     this.swGmapWidget.lng.val(center.lng());
     this.swGmapWidget.lat.val(center.lat());
  });
  
  // bind the click action on the map
  GEvent.addListener(this.map, "click", function(overlay, latlng) {
    if (latlng != null) {
      swGmapWidget.activeWidget = this.swGmapWidget;
      
      this.swGmapWidget.geocoder.getLocations(
        latlng, 
        swGmapWidget.reverseLookupCallback
      );
    }
  });
  
  // bind the click action on the lookup field
  this.lookup.bind('click', function(){
    swGmapWidget.activeWidget = this.swGmapWidget;
    
    this.swGmapWidget.geocoder.getLatLng(
      this.swGmapWidget.address.val(), 
      swGmapWidget.lookupCallback
    ); 
    
    return false;
  })
}

swGmapWidget.activeWidget = null;
swGmapWidget.lookupCallback = function(point)
{
  // get the widget and clear the state variable
  var widget = swGmapWidget.activeWidget;
  swGmapWidget.activeWidget = null;
  
  if (!point) {
    alert("address not found");
    return;
  }
  
  widget.map.clearOverlays();
  widget.map.setCenter(point, 15);
  var marker = new GMarker(point);
  widget.map.addOverlay(marker);
}

swGmapWidget.reverseLookupCallback = function(response)
{
  // get the widget and clear the state variable
  var widget = swGmapWidget.activeWidget;
  swGmapWidget.activeWidget = null;
  
  widget.map.clearOverlays();
  
  if (!response || response.Status.code != 200) {
    alert('no address found');
    return;
  }
  
  // get information location and init variables
  var place = response.Placemark[0];
  var point = new GLatLng(place.Point.coordinates[1],place.Point.coordinates[0]);
  var marker = new GMarker(point);
  
  // add marker and center the map
  widget.map.setCenter(point, 15);
  widget.map.addOverlay(marker);

  // update values
  widget.address.val(place.address);
  widget.lat.val(place.Point.coordinates[1]);
  widget.lng.val(place.Point.coordinates[0]);
}