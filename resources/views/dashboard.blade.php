<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WMP</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

</head>
<body>
    <header>
        <nav>
            <a href="">Home</a>
            <a href="{{ route('fitures') }}">Features</a>
            <a href="{{ route('login') }}">Login</a>
        </nav>
    </header>

    <!-- carousel -->
    <div class="carousel">
        <!-- list item -->
        <div class="list">
            <div class="item">
                <img src="images/Bruno Mars.jpg">
                <div class="content">
                    <div class="author">Group 7</div>
                    <div class="title">SOUNDHAVEN</div>
                    <div class="topic">Bruno Mars</div>
                    <div class="des">
                        <!-- lorem 50 -->
                        Lorem ipsum dolor, sit amet consectetur adipisicing elit. Ut sequi, rem magnam nesciunt minima placeat, itaque eum neque officiis unde, eaque optio ratione aliquid assumenda facere ab et quasi ducimus aut doloribus non numquam. Explicabo, laboriosam nisi reprehenderit tempora at laborum natus unde. Ut, exercitationem eum aperiam illo illum laudantium?
                    </div>
                    <div class="buttons">
                        <a href="https://www.youtube.com/channel/UCoUM-UJ7rirJYP8CQ0EIaHA" target="_blank">
                            <button>SUBSCRIBE</button>
                        </a>
                    </div>
                </div>
            </div>
            <div class="item">
                <img src="images/LiSa.jpg">
                <div class="content">
                    <div class="author">Group 7</div>
                    <div class="title">SOUNDHAVEN</div>
                    <div class="topic">LiSA</div>
                    <div class="des">
                        Lorem ipsum dolor, sit amet consectetur adipisicing elit. Ut sequi, rem magnam nesciunt minima placeat, itaque eum neque officiis unde, eaque optio ratione aliquid assumenda facere ab et quasi ducimus aut doloribus non numquam. Explicabo, laboriosam nisi reprehenderit tempora at laborum natus unde. Ut, exercitationem eum aperiam illo illum laudantium?
                    </div>
                    <div class="buttons">
                        <a href="https://www.youtube.com/channel/UC8xcPPVYvUxv1CPoEcqj4fQ" target="_blank">
                            <button>SUBSCRIBE</button>
                        </a>
                    </div>
                </div>
            </div>
            <div class="item">
                <img src="images/Raisa.jpeg">
                <div class="content">
                    <div class="author">Group 7</div>
                    <div class="title">SOUNDHAVEN</div>
                    <div class="topic">Raisa</div>
                    <div class="des">
                        Lorem ipsum dolor, sit amet consectetur adipisicing elit. Ut sequi, rem magnam nesciunt minima placeat, itaque eum neque officiis unde, eaque optio ratione aliquid assumenda facere ab et quasi ducimus aut doloribus non numquam. Explicabo, laboriosam nisi reprehenderit tempora at laborum natus unde. Ut, exercitationem eum aperiam illo illum laudantium?
                    </div>
                    <div class="buttons">
                        <a href="https://www.youtube.com/@lisaSMEJ/videos" target="_blank">
                            <button>SUBSCRIBE</button>
                        </a>
                    </div>
                </div>
            </div>
            <div class="item">
                <img src="images/ed sheeran.jpg">
                <div class="content">
                    <div class="author">Group 7</div>
                    <div class="title">SOUNDHAVEN</div>
                    <div class="topic">Ed Sheeran</div>
                    <div class="des">
                        Lorem ipsum dolor, sit amet consectetur adipisicing elit. Ut sequi, rem magnam nesciunt minima placeat, itaque eum neque officiis unde, eaque optio ratione aliquid assumenda facere ab et quasi ducimus aut doloribus non numquam. Explicabo, laboriosam nisi reprehenderit tempora at laborum natus unde. Ut, exercitationem eum aperiam illo illum laudantium?
                    </div>
                    <div class="buttons">
                        <a href="https://www.youtube.com/channel/UC0C-w0YjGpqDXGB8IHb662A" target="_blank">
                            <button>SUBSCRIBE</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- list thumnail -->
        <div class="thumbnail">
            <div class="item">
                <img src="images/Bruno Mars.jpg">
                <div class="content">
                    <div class="title">
                        BRUNO MARS
                    </div>
                    <div class="description">
                        Description
                    </div>
                </div>
            </div>
            <div class="item">
                <img src="images/LiSa.jpg">
                <div class="content">
                    <div class="title">
                        LiSA
                    </div>
                    <div class="description">
                        Description
                    </div>
                </div>
            </div>
            <div class="item">
                <img src="images/Raisa.jpeg">
                <div class="content">
                    <div class="title">
                        RAISA
                    </div>
                    <div class="description">
                        Description
                    </div>
                </div>
            </div>
            <div class="item">
                <img src="images/ed sheeran.jpg">
                <div class="content">
                    <div class="title">
                        ED SHEERAN
                    </div>
                    <div class="description">
                        Description
                    </div>
                </div>
            </div>
        </div>
        <!-- next prev -->

        <div class="arrows">
            <button id="prev"><</button>
            <button id="next">></button>
        </div>
        <!-- time running -->
        <div class="time"></div>
    </div>


    <script src="{{ asset('js/dashboard.js') }}"></script>
</body>
</html>
