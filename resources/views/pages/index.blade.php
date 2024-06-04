<x-app-layout>
    <h1>Link</h1>

    <form>
        <input type="text" id="url-input" placeholder="Enter URL" required>
        <button type="submit">Shorten</button>
    </form>

    <div id="shortened-link"></div>

    <hr>

    <h2>Latest Links</h2>
    <div id="latest-links"></div>

    <x-slot name="page_scripts">
        <script>
            // Function to copy link to clipboard
            function copyToClipboard(url) {
                navigator.clipboard.writeText(url);
            }

            $(document).ready(function() {
                var origin = window.location.origin

                // Function to shorten link
                function shortenLink() {
                    var urlInput = $('#url-input');
                    var url = urlInput.val();

                    $.ajax({
                        url: '{{ route('shorten') }}',
                        type: 'POST',
                        data: {
                            url: url,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            var shortenedLink =  origin + '/' + response.code;

                            var html = '<a href="' + shortenedLink + '" target="_blank">' + response.code + '</a> ';
                            html += '<button onclick="copyToClipboard(\'' + shortenedLink + '\')">Copy</button>';
                            $('#shortened-link').html(html);


                            urlInput.val('');
                            updateLatestLinks();
                        },
                        error: function(response) {
                            var error = response.responseJSON.message;
                            $('#shortened-link').text('Error: ' + error);
                        }
                    });
                }

                // form submit event
                $('form').on('submit', function(event) {
                    event.preventDefault();
                    shortenLink();
                });

                // Function to update latest links
                function updateLatestLinks() {
                    $.ajax({
                        url: '{{ route('latest.links') }}',
                        type: 'GET',
                        success: function(response) {
                            var latestLinksHtml = '';

                            response.forEach(function(link) {
                                var shortenedLink =  origin + '/' + link.code;
                                latestLinksHtml += '<p><a href="' + shortenedLink + '" target="_blank">' + link.code + '</a> ';
                                latestLinksHtml += '<button onclick="copyToClipboard(\'' + shortenedLink + '\')">Copy</button></p>';
                            });

                            $('#latest-links').html(latestLinksHtml);
                        }
                    });
                }

                // run updateLatestLinks() on page load
                updateLatestLinks();
            });
        </script>
    </x-slot>
</x-app-layout>
