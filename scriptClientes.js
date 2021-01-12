var formulario = document.getElementById("areaRegCliente");

$(document).ready(function () {
    // Listen to submit event on the <form> itself!
    $('#areaRegCliente').submit(function (e) {    
      id = document.getElementById("id").value;
      nom = document.getElementById("nombre").value;
      primerA = document.getElementById("apellidos").value;
      tel = document.getElementById("telefono").value;
      email = document.getElementById("email").value;
      matricula = document.getElementById("matricula").value;
      marca = document.getElementById("marca").value;
      mod = document.getElementById("modelo").value;
      color = document.getElementById("color").value;
      tam = document.getElementById("tamanio").value;
      pass = document.getElementById("contraseña").value;
      tipo = document.getElementById("tipo").value;
      if(comprobar(nom, primerA, tel, email, matricula, marca, mod, color, tam, tipo)){            
        //REGISTRO
        $.post("Estacionamiento/principal.php", {
          id: id,
          nombre: nom,
          apellidos: primerA,
          telefono: tel,
          correo: email,
          matri: matricula,
          modelo: mod,
          col: color,
          tamanio: tam,
          contraseña: pass,
          tipo: tipo
        }).complete(function() {
          limpiarCampos();     
        });                            
      }else{          
        e.preventDefault();
      }  
    });    
});

function comprobar(nombre, primer_apellido, telefono, email, matricula, marca, modelo, color, tam, tipo){   
  if((nombre != "")&&(nombre.match(/[(A-ZÁ-Úa-zá-ú)+\s?+]+/) == nombre)){      
      
      if((primer_apellido != "")&&(primer_apellido.match(/[(A-ZÁ-Úa-zá-ú)+\s?+]+/) == primer_apellido)){
        
            
        if((telefono != "")&&(telefono.match(/[1-9][0-9]{6,10}/) == telefono)){
                
           
            if((email != "")&&(email.match(/[_A-Za-z0-9-\+]+(\.[_A-Za-z0-9-]+)*@+[A-Za-z0-9-]+(\.[A-Za-z0-9]+)*(\.[A-Za-z]{2,})/g) == email)){
              
              if(tipo == "conductor"){
                if((matricula != "")&&((matricula.match(/[A-Z]{3}\-[0-9]{2}\-[0-9]{2}/) == matricula)||(matricula.match(/[A-Z]\-\d{3}\-[A-Z]{3}/)==matricula) ||(matricula.match(/\d{3}\-[A-Z]{3}/)==matricula)||(matricula.match(/[A-Z]\-\d{2}\-[A-Z]{3}/)==matricula))){
                    
                  if((marca!="")&&marca.match(/[(A-ZÁ-Úa-zá-ú0-9)+\s?+]+/)==marca){

                    if((modelo!="")&&modelo.match(/[(A-ZÁ-Úa-zá-ú0-9)+\s?+]+/)==modelo){

                      if((color!="")&&color.match(/[(A-ZÁ-Úa-zá-ú)+\s?+]+/)==color){
                        return true;
                      }else{ 
                        alert("Error en el color");
                        return false;
                      }

                    }else{ 
                      alert("Error en el modelo");
                      return false;
                    }
                  }else{ 
                    alert("Error en la marca");
                    return false;                    
                  }
                }else{               
                    alert("La matricula debe seguir estas reglas \nL = Letra, D = Digito \tFormatos válidos: \n(LLL-DD-DD, L-DDD-LLL, DDD-LLL o bien L-DD-LLL)");
                    return false;
                }
              }else{
                return true;
              }
            }else{                
              alert("Error en el email");
              return false;
            }
    
          }else{          
            alert("Error en el teléfono");
            return false;
          }

      }else{          
        alert("Error en apellidoso");
        return false;
      }
  }else{      
    alert("Error en el nombre");
    return false;
  }
}

function limpiarCampos(){    
    document.getElementById("nombre").value = "";
    document.getElementById("apePaterno").value = "";
    document.getElementById("apeMaterno").value = "";
    document.getElementById("telefono").value = "";
    document.getElementById("email").value = "";
    document.getElementById("matricula").value = "";
    document.getElementById("marca").value = "";
    document.getElementById("modelo").value = "";
    document.getElementById("color").value = "";
}

