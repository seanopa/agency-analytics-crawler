<template>
  <div class="content">

    <div class="row">
      <div class="small-12 medium-6 columns">
        <h1>Well, this is awkward...</h1>

        <p>As hard as we tried, we couldn't find the page you were looking for. Maybe you could try one of the pages on the right.</p>
        <p>Some people understand this problem as a 404 error.</p>

        <div class="back-to-front">
          <a><p>Get me back to the front page <span id="table-flip">(╯°□°）╯︵ ┻━┻</span></p></a>
        </div>

      </div>
      <div class="small-12 medium-6 columns">
        <div class="sidemenu">
          <ul class="menu vertical">
            <li><a href="/">Home</a></li>
          </ul>
        </div>
      </div>
    </div>

  </div>
</template>

<style type="text/css">
@charset "UTF-8";
.content {
  padding: 1.5rem;
}
.content p {
  width: 80%;
  display: inline-block;
}
.content h1 {
  font-size: 2.5rem;
}

.sidemenu {
  padding-top: 1em;
  width: 50%;
}
.sidemenu a {
  color: #000;
}
.sidemenu a:hover {
  background: blue;
  color: #fff;
}

.back-to-front {
  text-align: justify;
}
.back-to-front a {
  color: #000;
}
.back-to-front a:hover {
  color: blue;
}

.back-to-front a p span {
  position: relative;
  content: "(╯°□°）╯︵ ┻━┻";
}

.back-to-front:hover a p span {
  display: none;
}

.back-to-front:hover a p:after {
  content: "┬──┬ ノ( ゜-゜ノ)";
  padding-left: 20px;
  font-size: 14px;
}

#table-flip {
  font-size: 14px;
  padding-left: 20px;
}
</style>