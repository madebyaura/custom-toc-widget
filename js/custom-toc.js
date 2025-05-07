document.addEventListener('DOMContentLoaded', function () {
    const tocList = document.querySelector('.custom-toc-list');
    if (!tocList) return;

    const tocHeadings = document.querySelectorAll('[data-include-in-toc="yes"]');

    tocHeadings.forEach((el, index) => {
        const headingTag = el.querySelector('h1,h2,h3,h4,h5,h6');
        if (!headingTag) {
            return;
        } 
            

        if (!headingTag.id) {
            headingTag.id = 'toc-heading-' + index;
        }

        const title = el.getAttribute('data-toc-title') ?? `Section ${index + 1}`;
        const id = headingTag.id;


        if (id) {
            const li = document.createElement('li');
            const a = document.createElement('a');
            a.href = '#' + id;
            a.textContent = title;
            li.appendChild(a);
            tocList.appendChild(li);
        }
    });
});