<ul class="categoryWidget">
    <% control Categories %>
    <% if Count != "0" %>
    <li><a href="$Link" title="$Title">$Title<% if ShowCount == "Yes" %> ($Count)<% end_if %></a></li>
    <% end_if %>
    <% end_control %>
</ul>