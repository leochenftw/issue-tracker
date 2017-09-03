<section class="section">
    <div class="container">
        <h1 class="title is-1">Project: $Title</h1>
        <div class="columns">
            <div class="column is-4">
                <h2 class="is-2 title">Website</h2>
                <% if $Website %>
                    <% with $Website %>
                        <h3 class="title is-3"><a href="$Link">$Title</a></h3>
                        <dl class="">
                            <% loop $Environments %>
                                <dt class="is-bold">Environment</dt>
                                <dd class="">$Title</dd>
                                <dt class="is-bold">Internal URL</dt>
                                <dd class=""><% if $InternalURL %>$InternalURL<% else %>???<% end_if %>
                                </dd>
                                <dt class="is-bold">URL</dt>
                                <dd class=""><% if $WebsiteURL %>$WebsiteURL<% else %>???<% end_if %>
                                </dd>
                            <% end_loop %>
                        </dl>
                    <% end_with %>
                <% else %>
                    <p><a href="/admin/pages/edit/show/$ID">Start one</a></p>
                <% end_if %>
            </div>
            <div class="column ajax-content-tasks" data-endpoint="/api/v/1/task?ProjectID=$ID">
                <h2 class="title is-2">Tasks</h2>
                <div class="ajax-list content"></div>
                <div class="ajax-nav has-text-centered"><a class="button hide" href="#">Load more</a></div>
            </div>
        </div>
    </div>
</section>
