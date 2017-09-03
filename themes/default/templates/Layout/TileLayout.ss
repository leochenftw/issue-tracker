<section class="section">
    <div class="container">
        <div class="content">
            <h1 class="title is-1">$Title</h1>
            <% if $AllChildren %>
            <ul>
                <% loop $AllChildren %>
                <li><a href="$Link">$Title</a></li>
                <% end_loop %>
            </ul>
            <% end_if %>
        </div>
    </div>
</section>
