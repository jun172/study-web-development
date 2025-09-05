// js.js

// ---------- グローバル変数 ----------
let allTags = []; // タグ一覧

// ---------- DOM Elements ----------
const postsContainer = document.getElementById('posts');
const editModal = document.getElementById('editModal');
const closeModal = document.getElementById('closeModal');
const editForm = document.getElementById('editForm');
const editTagsContainer = document.getElementById('editTags');
const clearTagBtn = document.getElementById('clear-tag');

// ---------- 初期化 ----------
document.addEventListener('DOMContentLoaded', () => {
    // タグ一覧取得
    fetch('get_tags.php')
        .then(res => res.json())
        .then(data => allTags = data);

    // タグ検索ボタン
    document.querySelectorAll('.tag-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const tag = btn.dataset.tag;
            fetchPosts({ tag });
        });
    });

    // タグ検索クリア
    clearTagBtn?.addEventListener('click', () => fetchPosts());

    // 記事削除ボタン（イベント委譲）
    postsContainer.addEventListener('click', e => {
        if (e.target.classList.contains('delete-btn')) {
            const postId = e.target.dataset.id;
            if (!confirm()) return;
            deletePost(postId);
        }
        // 編集ボタン
        if (e.target.classList.contains('edit-btn')) {
            const postId = e.target.dataset.id;
            openEditModal(postId);
        }
    });

    // 編集モーダル閉じる
    closeModal?.addEventListener('click', () => editModal.style.display = 'none');

    // 編集フォーム送信
    editForm?.addEventListener('submit', e => {
        e.preventDefault();
        submitEditForm();
    });
});

// ---------- 関数 ----------

// 記事一覧をAjaxで取得
function fetchPosts(params = {}) {
    let url = 'index.php?ajax=1';
    if (params.tag) url += `&tag=${encodeURIComponent(params.tag)}`;

    fetch(url)
        .then(res => res.text())
        .then(html => {
            postsContainer.innerHTML = html;
            triggerFlashEffect();
        });
}

// 記事削除
function deletePost(postId) {
    fetch(`delete.php?id=${postId}&ajax=1`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const postDiv = document.querySelector(`.post[data-id='${postId}']`);
                postDiv.classList.add('fade-out'); // CSSでフェードアウト
                setTimeout(() => postDiv.remove(), 500);
            } else {
                alert('削除失敗: ' + data.msg);
            }
        });
}

// 編集モーダル表示
function openEditModal(postId) {
    fetch(`get_post.php?id=${postId}`)
        .then(res => res.json())
        .then(post => {
            document.getElementById('editId').value = post.id;
            document.getElementById('editTitle').value = post.title;
            document.getElementById('editContent').value = post.content;

            // タグチェックボックス生成
            editTagsContainer.innerHTML = '';
            allTags.forEach(tag => {
                const checked = post.tags_array.includes(tag.name) ? 'checked' : '';
                const label = document.createElement('label');
                label.innerHTML = `<input type="checkbox" name="tags[]" value="${tag.id}" ${checked}> ${tag.name}<br>`;
                editTagsContainer.appendChild(label);
            });

            editModal.style.display = 'flex';
        });
}

// 編集フォーム送信
function submitEditForm() {
    const formData = new FormData(editForm);
    fetch('edit_post.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                editModal.style.display = 'none';
                updatePostDom(data.post);
            } else {
                alert('更新失敗: ' + data.message);
            }
        });
}

// DOMの更新
function updatePostDom(post) {
    const postDiv = document.querySelector(`.post[data-id='${post.id}']`);
    postDiv.querySelector('.post-title').textContent = post.title;
    postDiv.querySelector('.post-content').innerHTML = post.content.replace(/\n/g, '<br>');
    postDiv.querySelector('.post-tags').textContent = post.tags;
    triggerFlashEffect();
}

// 光る演出（厨二風）
function triggerFlashEffect() {
    const posts = document.querySelectorAll('.post');
    posts.forEach(post => {
        post.classList.add('glow');
        setTimeout(() => post.classList.remove('glow'), 600);
    });
}
document.addEventListener('DOMContentLoaded',()=>{
    const postsContainer = document.getElementById('posts');

    // タグ検索
    document.querySelectorAll('.tag-btn').forEach(btn=>{
        btn.addEventListener('click', ()=>{
            const tag = btn.dataset.tag;
            location.href = 'index.php?tag=' + encodeURIComponent(tag);
        });
    });
    document.getElementById('clear-tag')?.addEventListener('click', ()=>location.href='index.php');

    // 削除
    postsContainer.addEventListener('click', e=>{
        if(e.target.classList.contains('delete-btn')){
            if(confirm('本当に削除しますか？')){
                const id = e.target.dataset.id;
                fetch('delete.php?id='+id).then(()=>location.reload());
            }
        }
    });

    // 編集ボタン（通常ページ遷移）
    postsContainer.addEventListener('click', e=>{
        if(e.target.classList.contains('edit-btn')){
            const id = e.target.dataset.id;
            location.href = 'edit.php?id=' + id;
        }
    });
});

// ランダムカラー生成
function randomColor() {
    const r = Math.floor(Math.random()*156 + 100); // 100~255
    const g = Math.floor(Math.random()*156 + 100);
    const b = Math.floor(Math.random()*156 + 100);
    return `rgb(${r},${g},${b})`;
}

// ボタン・タグにクリックイベント
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('button, .tag-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const color1 = randomColor();
            const color2 = randomColor();
            btn.style.background = `linear-gradient(45deg, ${color1}, ${color2})`;
        });
    });
});
