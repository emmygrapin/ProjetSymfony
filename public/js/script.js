var $collectionHolder;

//Ajout d'une caractéristique
var $addCharacteristicButton = $('<button type="button" class="add_characteristic_link">Ajouter une caractéristique</button>');

var $newLinkLi = $('<li></li>').append($addCharacteristicButton);

jQuery(document).ready(function(){
    //affiche la balise ul qui se nomme characteristics
    $collectionHolder = $('ul.characteristics');

    //ajoute une balise li avec un bouton "ajouter une caractéristique"
    $collectionHolder.append($newLinkLi);

    //compter le nombre d'inputs pour incrémenter le prochain
    $collectionHolder.data('index',$collectionHolder.find(':input').length);

    //définit l'action du bouton "ajouter une caractéristique"
    $addCharacteristicButton.on('click',function (e) {
        addCharacteristicForm($collectionHolder, $newLinkLi);

    });
});

function addCharacteristicForm($collectionHolder,$newLinkLi){

    var prototype = $collectionHolder.data('prototype');
    var index = $collectionHolder.data('index');
    var newForm = prototype;
    newForm = newForm.replace(/__name__/g,index);

    $collectionHolder.data('index', index + 1);

    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);

}