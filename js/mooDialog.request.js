MooDialog.Request = new Class({

   Extends: MooDialog,

   initialize: function(url,reqOptions,options) {

         this.parent(options);
 
         this.setContent(
              new Element('div').set('load',reqOptions).load(url)
         ).open();
   }  

});//end class MooDialog.Request