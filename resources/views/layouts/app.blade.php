<!doctype html>
<html
  lang="en"
  class="layout-menu-fixed layout-compact"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free">

  @include('partials.header')

  <body>


    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">

        {{-- Sidebar --}}
        @include('partials.sidebar')

        <!-- Layout container -->
        <div class="layout-page">

          {{-- Navbar --}}
          @include('partials.navbar')

          {{-- Main Content --}}
          <div class="content-wrapper">

            @yield('content')

            {{-- Footer --}}
            @include('partials.footer')


            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->


    <!-- Core JS -->
@include('partials.scripts')

  </body>
</html>

