if (session_state && document.querySelector(".image-popup")) {
    var image_popup = document.querySelector(".image-popup");
    document.querySelectorAll(".image a").forEach(img_link => {
        img_link.onclick = e => {
            // window part
            e.preventDefault();
            var img_meta = img_link.querySelector("div img");
            var img = new Image();
            img.onload = () => {
                image_popup.innerHTML = `
            <div class = "con">
                <section class="legend_part">
                    <h3>${img_meta.title} by ${img_meta.dataset.username}</h3>
                    <i class="image_date">${img_meta.dataset.date}<i/>
                    <p>${img_meta.alt}</p>
                </section>
                <section class="image_part">
                    <img src="${img.src}" width="360" height="240">
                    ${img_meta.dataset.comments}
                </section>
                <section class="button_part">
                    <a href="/camagru/image/delete/${img_meta.dataset.id}/${img_meta.title}" class="trash" title="Delete Image">
                        <i class="fas fa-trash fa-xs"></i>
                    </a>
                    <a href="/camagru/image/like/${img_meta.dataset.id}" class=${img_meta.dataset.liked} title="Like Image">
                        <i class="fas fa-heart fa-xs"></i>
                        <p class="likes_number">${img_meta.dataset.likes}</p>
                    </a>
                </section>
                <section class="comment_part">
                    <form method="post" action="/camagru/image/comment/${img_meta.dataset.id}">
                        <textarea name="comment" rows=4 cols=50 required></textarea>
                        <input class="green-button" type="submit" value="Add comment"></input>
                    </form>
                </section>
            </div>`;
                image_popup.style.display = "flex";
            };
            img.src = img_meta.src;
        };
    });

    image_popup.onclick = e => {
        if (e.target.className == "image-popup") {
            image_popup.style.display = "none";
        }
    };
}
