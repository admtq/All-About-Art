//

// popup post
document.getElementById("openPopupPost").onclick = function() {
    document.getElementById("popupPost").style.display = "block";
}

document.getElementById("closePopupPost").onclick = function() {
    document.getElementById("popupPost").style.display = "none";
}

// Tutup pop-up jika pengguna mengklik di luar pop-up
window.onclick = function(event) {
    const popupPost = document.getElementById("popupPost");
    if (event.target == popupPost) {
        popupPost.style.display = "none";
    }
}