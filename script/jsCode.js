

function populateNote(id, title, desc, dates){
    // document .getElementById('nid').innerHTML = id;
    document .getElementById('ntitle').innerHTML = title;
    document .getElementById('ndesc').innerHTML = desc;
    document .getElementById('ndate').innerHTML = dates;
    console.log(id,title,desc,dates);
}

function openForm(){
    document.getElementById('overNote').style.display = 'flex';
    document.getElementById('title').value = "";
    document.getElementById('descrip').value = "";
}
function openNote(){
    document.getElementById('viewNote').style.display = 'flex';

}
function closeNote(){
    document.getElementById('viewNote').style.display = 'none';

}
function closeForm(){
    document.getElementById('overNote').style.display = 'none';
    document.getElementById('title').value = "";
    document.getElementById('descrip').value = "";
}
// function submited(){
//     document.getElementById('title').value = "";
//     document.getElementById('descrip').value = "";
// }
// views.addEventListener('click',openNote);
addNote.addEventListener('click',openForm);
// cancel.addEventListener('click',closeForm);
// add.addEventListener('click', submited);

function idTodele(id, title, desc, dates, star, arc){
    document.getElementById('noteId').value = id;
    document.getElementById('nameDel').innerHTML = "Are you Sure to Remove "+title+"?";
    document.getElementById('descri').value = desc;
    document.getElementById('titil').value = title;
    document.getElementById('dats').value = dates;
    document.getElementById('str').value = star;
    document.getElementById('arch').value = arc;
    document.getElementById('overlayNote').style.display = 'flex';
}
function cancelDel(){
    document.getElementById('noteId').value = "";
    document.getElementById('nameDel').innerHTML = "";
    document.getElementById('overlayNote').style.display = 'none';
}
function showTab(tabNumber) {
   
    document.querySelectorAll('.tab-content').forEach(function(content) {
        content.classList.remove('active');
    });

   
    document.querySelector('.tab' + tabNumber + '-content').classList.add('active');
}

function removeData(id, title){
    document.getElementById('dataId').value = id;
    document.getElementById('dataNm').innerHTML = "Are you Sure to delete "+title+"?";
    document.getElementById('overlayNote').style.display = 'flex';
    }

    function removeCancel(){
    document.getElementById('dataId').value = "";
    document.getElementById('dataNm').innerHTML = "";
    document.getElementById('overlayNote').style.display = 'none';
}

const textarea = document.getElementById('myTextarea');
const charCount = document.getElementById('charCount');
const maxChars = 255;

textarea.addEventListener('input', function() {
    const text = textarea.value;
    const remainingChars = maxChars - text.length;

    charCount.textContent = text.length + ' / ' + maxChars;

   
    if (remainingChars <= 0) {
        // Disable further input if the character limit is reached
        textarea.value = text.slice(0, maxChars);
    }
});