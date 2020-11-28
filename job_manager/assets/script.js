jQuery('.tab-conreols li').on("click", function(){
    jQuery(this).siblings('.li-active').removeClass('li-active');
    jQuery('.job-tab').removeClass('active-tab');
    var ref = jQuery(this).attr('ref');
    jQuery(ref).addClass('active-tab');
    jQuery(this).addClass('li-active');
});



acf.do_action('ready', jQuery('body'));

  // will be used to check if a form submit is for validation or for saving
  let isValidating = false;

  acf.add_action('validation_begin', () => {
    isValidating = true;
  });

  acf.add_action('submit', ($form) => {
    isValidating = false;
  });

  jQuery('.acf-form').on('submit', (e) => {
    let $form = jQuery(e.target);
    e.preventDefault();
    // if we are not validating, save the form data with our custom code.
    if( !isValidating ) {
      // lock the form
       
      jQuery.ajax({
        url: window.location.href,
        method: 'post',
        data: $form.serialize(),
        success: () => {
          // unlock the form
           
        }
      });
    }


  });
jQuery('.status-container select').on('change', function(){
    jQuery('.status-container .acf-form-submit input').trigger('click');
})
 
jQuery('.status-container-2 input').on('change', function(){
    jQuery('.status-container .acf-form-submit input').trigger('click');
})
 

function showEditPopup(){
    jQuery('.edit-popup').show();
}

function hideEditPopup(){
    jQuery('.edit-popup').hide();
}

jQuery('.edit-popup form').on('blur','#ok',function(){
    console.log('blur');
});

jQuery(document).on('click', function(e) {
    if ( e.target.id != 'pop-inner'  && e.target.id != 'but-pop'  ) {
         
    }
});

jQuery('.edit-popup').on('click',':not(form)',function(){
    //hideEditPopup();
})

jQuery(".popclose").on("click", function(){
    hideEditPopup();
  });
  
  
  jQuery(".edit-popup form").on('submit', function(){
      setTimeout(() => {
          window.location.reload();
      }, 1000);
  })

  jQuery('.select2-selection__choice__remove').on("click",function(){
      return confirm('Are you sure?');
  })

  var box, oldValue='';
  box = document.querySelector('.status-container select');
  if (box.addEventListener) {
    box.addEventListener("change", changeHandler, false);
  }
  else if (box.attachEvent) {
    box.attachEvent("onchange", changeHandler);
  }
  else {
    box.onchange = changeHandler;
  }
  function changeHandler(event) {
    var index, newValue;
    index = this.selectedIndex;
    if (index >= 0 && this.options.length > index) {
      newValue = this.options[index].value;
    }
    var answer 
    if(newValue == 'Paused' || newValue == 'Closed'){
      answer = confirm("Are you sure? If you set the job to "+newValue+", your Split Partners will no longer be able to submit candidates to this role");    
    }
    else{
      answer = confirm("Are you sure?");
    }if(answer)
    {
      oldValue = newValue;
      console.log('case1');
    }else{
      box.value = oldValue;
      console.log('case2');
    }
  }

  jQuery('.acf-description-form-submit').on("click", function(){
    console.log("here");
    jQuery(this).addClass("btn-loading");
    jQuery(this).parent().parent().find(".acf-form-submit").children("input").trigger("click");
  });