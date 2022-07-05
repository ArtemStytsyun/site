  //const axios = require('../../../node_modules/axios/index.js');
  // import axios from 'C:/wamp64/www/processwire-dev/node_modules/axios/index.js';


  // axios = require('axios');
  // axios.get('https://fortnite-api.com/v1/map').then(response=>{
  //   console.log(axios)
  // }).catch(error=>{
  //   console.error(error)
  // });





  //Счиетчики товаров
  $('.product .plus').click(function(event){
 
    //Получаем количество и имя продукта, замечание - продукт может быть самым бредовым, необходима проверка на сервере
    let number = Number($(event.target.offsetParent).find('.number')[0].innerText)
    let productName = $(event.target.offsetParent).find('.product__name')[0].innerText
    
    //if(number <=0 ){return}

    //посылаем ajax, если не больше максимального количества, то ++
    $.ajax({
      url: document.location.href,
      type: 'POST',
      cache: 'false',
      data:{
        'productName': productName,
        'flagInc': 'plus'
      }, 
      dataType: 'json',
      beforeSend: function(){
        $(event).prop('disabled', true);
      },
      success: function(data) {
        if(data.product_max_number >= number){
          number = data.product_number;
          
          $(event.target.offsetParent).find('.number')[0].innerHTML = number
          $('.price').text(data.user_price)

          //$(event).prop('disabled', false);
        }
        else number = 0;
        
      }
    })
  })

  $('.product .minus').click(function(event){
    //Получаем количество и имя продукта, замечание - продукт может быть самым бредовым, необходима проверка на сервере
    let number = Number($(event.target.offsetParent).find('.number')[0].innerText)
    let productName = $(event.target.offsetParent).find('.product__name')[0].innerText

    //if(number <=0 ){return}

    //посылаем ajax, если не больше максимального количества, то ++
    $.ajax({
      url: document.location.href,
      type: 'POST',
      cache: 'false',
      data:{
        'productName': productName,
        'flagInc': 'minus'
      }, 
      dataType: 'json',
      beforeSend: function(){
        //$(event).prop('disabled', true);
      },
      success: function(data) {
        if(data.product_max_number >= number && number > 0){
          number = data.product_number;
          $(event.target.offsetParent).find('.number')[0].innerHTML = number
          $('.price').text(data.user_price)
          //$(event).prop('disabled', false);
          //костыль надо исправить
          if(number == 0 && document.location.href == 'http://localhost/processwire-dev/Basket/'){
            //удаление обьекта
            console.log(event.target.offsetParent);
            $(event.target.offsetParent).remove();
          }
          
        }
        else number = 0;
        
      }
    })
  })

  
  $('.pay').click(function(event){
    console.log(1)
    $.ajax({
      url: document.location.href,
      type: 'POST',
      cache: 'false',
      data:{
        'pay': 'pay'
      }, 
      dataType: 'json',
      beforeSend: function(){
        $(event).prop('disabled', true);
      },
      success: function(data) {
        if(data.success){
          $('.product').remove()
          $('.price').text('0')
        }
        else{
          console.log('error');
        }
      }
    })
  })

var forms = document.querySelectorAll('.needs-validation')
console.log(forms)

//предотвращение отправки
Array.prototype.slice.call(forms)
  .forEach(function (form) {
    form.addEventListener('submit', function (event) {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }

      form.classList.add('was-validated')
    }, false)
  })
