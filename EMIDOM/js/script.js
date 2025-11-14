const initSlider = async () => {
    const imageList = document.querySelector(".slider-wrapper .image-list");
    const slideButtons = document.querySelectorAll(".slider-wrapper .slide-button");
    const sliderScrollbar = document.querySelector(".container .slider-scrollbar");
    const scrollbarThumb = sliderScrollbar.querySelector(".scrollbar-thumb");

    const maxScrollLeft = imageList.scrollWidth - imageList.clientWidth;

    try {
        const url = 'https://myanimelist.p.rapidapi.com/anime/top/all?p=1';
        const options = {
        method: 'GET',
        headers: {
            'x-rapidapi-key': 'd2aa070660msh67c1d1e950a983dp1ae9e3jsnd3ac570aca0b',
            'x-rapidapi-host': 'myanimelist.p.rapidapi.com'
        }
    };

        const response = await fetch(url, options);
        const data = await response.json();

        // Clear any existing content in the image list
        imageList.innerHTML = '';

        // Iterate over the first 12 anime and create image list items
        data.slice(0, 12).forEach((anime, index) => {
            const img = document.createElement('img');
            img.src = anime.picture_url; // Image URL
            img.alt = anime.title;       // Alt text
            img.dataset.myanimeId = anime.myanimelist_id; // Dataset

            img.style.width = '200px';
            img.style.height = '300px';
            img.classList.add('image-item');

            const anchor = document.createElement('a');
            anchor.href = `./pages/anime.php?id=${anime.myanimelist_id}`;
            anchor.appendChild(img);

            anchor.addEventListener('mouseenter', () => {
                img.style.opacity = '0.7';
                img.style.transition = 'opacity 0.3s ease';
            });

            anchor.addEventListener('mouseleave', () => {
                img.style.opacity = '1';
            });

            imageList.appendChild(anchor);
        });

    } catch (error) {
        console.error(error);
    }

    // Handle scrollbar thumb drag
    scrollbarThumb.addEventListener("mousedown", (e) => {
        const startX = e.clientX;
        const thumbPosition = scrollbarThumb.offsetLeft;
        const maxThumbPosition = sliderScrollbar.getBoundingClientRect().width - scrollbarThumb.offsetWidth;

        // Update thumb position on mouse move
        const handleMouseMove = (e) => {
            const deltaX = e.clientX - startX;
            const newThumbPosition = thumbPosition + deltaX;

            // Ensure the scrollbar thumb stays within bounds
            const boundedPosition = Math.max(0, Math.min(maxThumbPosition, newThumbPosition));
            const scrollPosition = (boundedPosition / maxThumbPosition) * maxScrollLeft;

            scrollbarThumb.style.left = `${boundedPosition}px`;
            imageList.scrollLeft = scrollPosition;
        }

        // Remove event listeners on mouse up
        const handleMouseUp = () => {
            document.removeEventListener("mousemove", handleMouseMove);
            document.removeEventListener("mouseup", handleMouseUp);
        }

        // Add event listeners for drag interaction
        document.addEventListener("mousemove", handleMouseMove);
        document.addEventListener("mouseup", handleMouseUp);
    });

    // Slide images according to the slide button clicks
    slideButtons.forEach(button => {
        button.addEventListener("click", () => {
            const direction = button.id === "prev-slide" ? -1 : 1;
            const scrollAmount = imageList.clientWidth * direction;
            imageList.scrollBy({ left: scrollAmount, behavior: "smooth" });
        });
    });

    // Show or hide slide buttons based on scroll position
    const handleSlideButtons = () => {
        slideButtons[0].style.display = imageList.scrollLeft <= 0 ? "none" : "flex";
        slideButtons[1].style.display = imageList.scrollLeft >= maxScrollLeft ? "none" : "flex";
    }

    // Update scrollbar thumb position based on image scroll
    const updateScrollThumbPosition = () => {
        const scrollPosition = imageList.scrollLeft;
        const thumbPosition = (scrollPosition / maxScrollLeft) * (sliderScrollbar.clientWidth - scrollbarThumb.offsetWidth);
        scrollbarThumb.style.left = `${thumbPosition}px`;
    }

    // Call these two functions when image list scrolls
    imageList.addEventListener("scroll", () => {
        updateScrollThumbPosition();
        handleSlideButtons();
    });
}

window.addEventListener("load", initSlider);
window.addEventListener("resize", initSlider);

const mangaInitSlider = async () => {
    const imageList = document.querySelector(".manga-slider-wrapper .manga-image-list");
    const slideButtons = document.querySelectorAll(".manga-slider-wrapper .manga-slide-button");
    const sliderScrollbar = document.querySelector(".container .manga-slider-scrollbar");
    const scrollbarThumb = sliderScrollbar.querySelector(".manga-scrollbar-thumb");

    const maxScrollLeft = imageList.scrollWidth - imageList.clientWidth;

    try {
        const url = 'https://myanimelist.p.rapidapi.com/manga/top/all?p=1';
        const options = {
            method: 'GET',
            headers: {
                'x-rapidapi-key': 'd2aa070660msh67c1d1e950a983dp1ae9e3jsnd3ac570aca0b',
                'x-rapidapi-host': 'myanimelist.p.rapidapi.com'
            }
        };

        const response = await fetch(url, options);
        const data = await response.json();

        // Clear any existing content in the image list
        imageList.innerHTML = '';

        // Iterate over the first 10 anime and create image list items
        data.slice(0, 12).forEach((anime, index) => {
            // Create and set content for the image
            const img = document.createElement('img');
            img.src = anime.picture_url; // Assuming image URL is in the 'picture_url' property
            img.alt = anime.title_en; // Set alt text to anime title
            img.dataset.myanimeId = anime.id; // Set dataset attribute for MyAnimeList ID
            img.style.width = '200px';
            img.style.height = '300px';
            img.classList.add('manga-image-item');

            // Create anchor tag for each image
            const anchor = document.createElement('a');
            anchor.href = `./pages/anime.php?id=${anime.myanimelist_id}`; // Adjust path and query parameter as needed
            anchor.appendChild(img); // Append the image to the anchor

            // Add hover effect
            anchor.addEventListener('mouseenter', () => {
                img.style.opacity = '0.7'; // Reduce opacity on hover
                img.style.transition = 'opacity 0.3s ease'; // Smooth transition effect
            });

            anchor.addEventListener('mouseleave', () => {
                img.style.opacity = '1'; // Restore opacity on mouse leave
            });

            // Append the anchor tag to the image list
            imageList.appendChild(anchor);
        });

    } catch (error) {
        console.error(error);
    }

    // Handle scrollbar thumb drag
    scrollbarThumb.addEventListener("mousedown", (e) => {
        const startX = e.clientX;
        const thumbPosition = scrollbarThumb.offsetLeft;
        const maxThumbPosition = sliderScrollbar.getBoundingClientRect().width - scrollbarThumb.offsetWidth;

        // Update thumb position on mouse move
        const handleMouseMove = (e) => {
            const deltaX = e.clientX - startX;
            const newThumbPosition = thumbPosition + deltaX;

            // Ensure the scrollbar thumb stays within bounds
            const boundedPosition = Math.max(0, Math.min(maxThumbPosition, newThumbPosition));
            const scrollPosition = (boundedPosition / maxThumbPosition) * maxScrollLeft;

            scrollbarThumb.style.left = `${boundedPosition}px`;
            imageList.scrollLeft = scrollPosition;
        }

        // Remove event listeners on mouse up
        const handleMouseUp = () => {
            document.removeEventListener("mousemove", handleMouseMove);
            document.removeEventListener("mouseup", handleMouseUp);
        }

        // Add event listeners for drag interaction
        document.addEventListener("mousemove", handleMouseMove);
        document.addEventListener("mouseup", handleMouseUp);
    });

    // Slide images according to the slide button clicks
    slideButtons.forEach(button => {
        button.addEventListener("click", () => {
            const direction = button.id === "manga-prev-slide" ? -1 : 1;
            const scrollAmount = imageList.clientWidth * direction;
            imageList.scrollBy({ left: scrollAmount, behavior: "smooth" });
        });
    });

    // Show or hide slide buttons based on scroll position
    const handleSlideButtons = () => {
        slideButtons[0].style.display = imageList.scrollLeft <= 0 ? "none" : "flex";
        slideButtons[1].style.display = imageList.scrollLeft >= maxScrollLeft ? "none" : "flex";
    }

    // Update scrollbar thumb position based on image scroll
    const updateScrollThumbPosition = () => {
        const scrollPosition = imageList.scrollLeft;
        const thumbPosition = (scrollPosition / maxScrollLeft) * (sliderScrollbar.clientWidth - scrollbarThumb.offsetWidth);
        scrollbarThumb.style.left = `${thumbPosition}px`;
    }

    // Call these two functions when image list scrolls
    imageList.addEventListener("scroll", () => {
        updateScrollThumbPosition();
        handleSlideButtons();
    });
}

window.addEventListener("resize", mangaInitSlider);
window.addEventListener("load", mangaInitSlider);

const tvNewInitSlider = async () => {
    const imageList = document.querySelector(".tvNew-slider-wrapper .tvNew-image-list");
    const slideButtons = document.querySelectorAll(".tvNew-slider-wrapper .tvNew-slide-button");
    const sliderScrollbar = document.querySelector(".container .tvNew-slider-scrollbar");
    const scrollbarThumb = sliderScrollbar.querySelector(".tvNew-scrollbar-thumb");

    const maxScrollLeft = imageList.scrollWidth - imageList.clientWidth;

    try {
        const url = 'https://myanimelist.p.rapidapi.com/v2/anime/seasonal?year=2023&season=winter';
        const options = {
            method: 'GET',
            headers: {
                'x-rapidapi-key': 'd2aa070660msh67c1d1e950a983dp1ae9e3jsnd3ac570aca0b',
		        'x-rapidapi-host': 'myanimelist.p.rapidapi.com'
            }
        };

        const response = await fetch(url, options);
        const data = await response.json();

        // Clear any existing content in the image list
        imageList.innerHTML = '';

        // Iterate over the first 10 entries in the "TV (New)" section of the array and create image list items
        data['TV (New)'].slice(0, 12).forEach((anime, index) => {
            // Create and set content for the image
            const img = document.createElement('img');
            img.src = anime.picture_url; // Use the image_url property for the image source
            img.alt = anime.title; // Set alt text to anime title
            img.dataset.myanimeId = anime.myanimelist_id; // Set dataset attribute for anime title
            img.style.width = '200px';
            img.style.height = '300px';
            img.classList.add('tvNew-image-item');

            // Create anchor tag for each image
            const anchor = document.createElement('a');
            anchor.href = `./pages/anime.php?id=${anime.myanimelist_id}`; // Adjust path and query parameter as needed
            anchor.appendChild(img); // Append the image to the anchor

            // Add hover effect
            anchor.addEventListener('mouseenter', () => {
                img.style.opacity = '0.7'; // Reduce opacity on hover
                img.style.transition = 'opacity 0.3s ease'; // Smooth transition effect
            });

            anchor.addEventListener('mouseleave', () => {
                img.style.opacity = '1'; // Restore opacity on mouse leave
            });

            // Append the anchor tag to the image list
            imageList.appendChild(anchor);
        });

    } catch (error) {
        console.error(error);
    }

    // Handle scrollbar thumb drag
    scrollbarThumb.addEventListener("mousedown", (e) => {
        const startX = e.clientX;
        const thumbPosition = scrollbarThumb.offsetLeft;
        const maxThumbPosition = sliderScrollbar.getBoundingClientRect().width - scrollbarThumb.offsetWidth;

        // Update thumb position on mouse move
        const handleMouseMove = (e) => {
            const deltaX = e.clientX - startX;
            const newThumbPosition = thumbPosition + deltaX;

            // Ensure the scrollbar thumb stays within bounds
            const boundedPosition = Math.max(0, Math.min(maxThumbPosition, newThumbPosition));
            const scrollPosition = (boundedPosition / maxThumbPosition) * maxScrollLeft;

            scrollbarThumb.style.left = `${boundedPosition}px`;
            imageList.scrollLeft = scrollPosition;
        }

        // Remove event listeners on mouse up
        const handleMouseUp = () => {
            document.removeEventListener("mousemove", handleMouseMove);
            document.removeEventListener("mouseup", handleMouseUp);
        }

        // Add event listeners for drag interaction
        document.addEventListener("mousemove", handleMouseMove);
        document.addEventListener("mouseup", handleMouseUp);
    });

    // Slide images according to the slide button clicks
    slideButtons.forEach(button => {
        button.addEventListener("click", () => {
            const direction = button.id === "tvNew-prev-slide" ? -1 : 1;
            const scrollAmount = imageList.clientWidth * direction;
            imageList.scrollBy({ left: scrollAmount, behavior: "smooth" });
        });
    });

    // Show or hide slide buttons based on scroll position
    const handleSlideButtons = () => {
        slideButtons[0].style.display = imageList.scrollLeft <= 0 ? "none" : "flex";
        slideButtons[1].style.display = imageList.scrollLeft >= maxScrollLeft ? "none" : "flex";
    }

    // Update scrollbar thumb position based on image scroll
    const updateScrollThumbPosition = () => {
        const scrollPosition = imageList.scrollLeft;
        const thumbPosition = (scrollPosition / maxScrollLeft) * (sliderScrollbar.clientWidth - scrollbarThumb.offsetWidth);
        scrollbarThumb.style.left = `${thumbPosition}px`;
    }

    // Call these two functions when image list scrolls
    imageList.addEventListener("scroll", () => {
        updateScrollThumbPosition();
        handleSlideButtons();
    });
}

window.addEventListener("resize", tvNewInitSlider);
window.addEventListener("load", tvNewInitSlider);

document.addEventListener('DOMContentLoaded', async function () {
    // Extract anime_id from the URL
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const animeId = urlParams.get('id');

    // Check if animeId is present in the URL
    if (animeId) {
        // Construct the URL for fetching anime details
        const url = `https://myanimelist.p.rapidapi.com/anime/${animeId}`;
        const options = {
            method: 'GET',
            headers: {
                'x-rapidapi-key': 'd2aa070660msh67c1d1e950a983dp1ae9e3jsnd3ac570aca0b',
                'x-rapidapi-host': 'myanimelist.p.rapidapi.com'
            }
        };

        try {
            // Fetch anime details
            const response = await fetch(url, options);
            const result = await response.json();

            // Update the h1 tag with the title of the anime
            const titleElement = document.getElementById('title_ov');
            if (titleElement) {
                titleElement.textContent = result.title_ov || ''; // Assuming the title is in the 'title_ov' property
            } else {
                console.error('Element with id "title_ov" not found.');
            }

            // Update the image source with the picture_url of the anime
            const pictureUrlElement = document.getElementById('picture_url');
            if (pictureUrlElement) {
                pictureUrlElement.src = result.picture_url || ''; // Assuming the picture_url is in the 'picture_url' property
            } else {
                console.error('Element with id "picture_url" not found.');
            }

            // Update the synopsis
            const synopsisElement = document.getElementById('synopsis');
            if (synopsisElement) {
                synopsisElement.textContent = result.synopsis || ''; // Assuming the synopsis is in the 'synopsis' property
            } else {
                console.error('Element with id "synopsis" not found.');
            }

            // Update the Type
            const typeElement = document.getElementById('type');
            if (typeElement) {
                typeElement.textContent = `Type: ${result.information.type[0].name}` || ''; // Assuming the type is in the 'type' property
            } else {
                console.error('Element with id "type" not found.');
            }

            // Update the Episodes
            const episodesElement = document.getElementById('episodes');
            if (episodesElement) {
                episodesElement.textContent = `Episodes: ${result.information.episodes}` || ''; // Assuming the episodes is in the 'episodes' property
            } else {
                console.error('Element with id "episodes" not found.');
            }

            // Update the Status
            const statusElement = document.getElementById('status');
            if (statusElement) {
                statusElement.textContent = `Status: ${result.information.status}` || ''; // Assuming the status is in the 'status' property
            } else {
                console.error('Element with id "status" not found.');
            }

            // Update the Aired
            const airedElement = document.getElementById('aired');
            if (airedElement) {
                airedElement.textContent = `Aired: ${result.information.aired}` || ''; // Assuming the aired is in the 'aired' property
            } else {
                console.error('Element with id "aired" not found.');
            }

            // Update the Premiered
            const premieredElement = document.getElementById('premiered');
            if (premieredElement) {
                premieredElement.textContent = `Premiered: ${result.information.premiered[0].name}` || ''; // Assuming the premiered is in the 'premiered' property
            } else {
                console.error('Element with id "premiered" not found.');
            }

            // Update the Broadcast
            const broadcastElement = document.getElementById('broadcast');
            if (broadcastElement) {
                broadcastElement.textContent = `Broadcast: ${result.information.broadcast}` || ''; // Assuming the broadcast is in the 'broadcast' property
            } else {
                console.error('Element with id "broadcast" not found.');
            }

            // Update the Producers
            const producersElement = document.getElementById('producers');
            if (producersElement) {
                if (result.information.producers && result.information.producers.length > 0) {
                    let producersText = 'Producers: ';
                    result.information.producers.forEach((producers, index) => {
                        producersText += producers.name;
                        if (index < result.information.producers.length - 1) {
                            producersText += ', ';
                        }
                    });
                    producersElement.textContent = producersText;
                } else {
                    producersElement.textContent = 'Producers: None';
                }
            } else {
                console.error('Element with id "producers" not found.');
            }

            // Update the Studios
            const studiosElement = document.getElementById('studios');
            if (studiosElement) {
                studiosElement.textContent = `Studios: ${result.information.studios[0].name}` || ''; // Assuming the studios is in the 'studios' property
            } else {
                console.error('Element with id "studios" not found.');
            }

            // Update the Source
            const sourceElement = document.getElementById('source');
            if (sourceElement) {
                sourceElement.textContent = `Source: ${result.information.source}` || ''; // Assuming the source is in the 'source' property
            } else {
                console.error('Element with id "source" not found.');
            }

            // Update the Genres
            const genresElement = document.getElementById('genres');
            if (genresElement) {
                if (result.information.genres.length > 0) {
                    let genresText = 'Genres: ';
                    result.information.genres.forEach((genre, index) => {
                        genresText += genre.name;
                        if (index < result.information.genres.length - 1) {
                            genresText += ', ';
                        }
                    });
                    genresElement.textContent = genresText;
                } else {
                    genresElement.textContent = 'Genres: None';
                }
            } else {
                console.error('Element with id "genres" not found.');
            }

            // Update the Demographic
            const demographicElement = document.getElementById('demographic');
            if (demographicElement) {
                demographicElement.textContent = `Demographic: ${result.information.demographic[0].name}` || ''; // Assuming the demographic is in the 'demographic' property
            } else {
                console.error('Element with id "demographic" not found.');
            }

            // Update the Duration
            const durationElement = document.getElementById('duration');
            if (durationElement) {
                durationElement.textContent = `Duration: ${result.information.duration}` || ''; // Assuming the duration is in the 'duration' property
            } else {
                console.error('Element with id "duration" not found.');
            }

            // Update the Rating
            const ratingElement = document.getElementById('rating');
            if (ratingElement) {
                ratingElement.textContent = `Rating: ${result.information.rating}` || ''; // Assuming the rating is in the 'rating' property
            } else {
                console.error('Element with id "rating" not found.');
            }
        } catch (error) {
            console.error('Error fetching or parsing data:', error);
        }
    } else {
        console.error('animeId not found in the URL.');
    }
});

