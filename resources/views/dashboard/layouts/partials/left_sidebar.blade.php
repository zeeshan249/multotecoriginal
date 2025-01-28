<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel" style="background-color: #fff; text-align: center;">
      <!--div class="pull-left image">
        @if(Auth::user()->image != '' && Auth::user()->image != null)
        <img src="{{ asset('public/uploads/user_images/thumb/'. Auth::user()->image) }}" class="img-circle" alt="User Image">
        @else
        <img src="{{ asset('public/images/user_image.png') }}" class="img-circle" alt="User Image">
        @endif
      </div>
      <div class="pull-left info">
        <p>{{ ucfirst(Auth::user()->first_name) }}</p>
        <a href="javascript:void(0);"><i class="fa fa-circle text-success"></i> Online</a>
      </div-->
      <a href="{{ route('dashboard') }}" id="brandBox">
        <img src="{{ asset('public/images/brand.png') }}" class="brandBox_logo">
      </a>
    </div>
    <!-- search form -->
    <!--form action="#" method="get" class="sidebar-form">
      <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="Search...">
        <span class="input-group-btn">
              <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
              </button>
            </span>
      </div>
    </form-->
    <!-- /.search form -->
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">MAIN NAVIGATION</li>
      
      <li class="">
        <a href="{{ route('dashboard') }}">
          <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
      </li>

      <li class="treeview @if(isset($GparentMenu) && $GparentMenu == 'management') active @endif">
        <a href="#">
          <i class="fa fa-bars" aria-hidden="true"></i> <span>Management</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          
          <li class="treeview @if(isset($parentMenu) && $parentMenu == 'userManagement') active @endif">
            <a href="#">
              <i class="fa fa-users" aria-hidden="true"></i> <span>Users Management</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class="@if(isset($childMenu) && $childMenu == 'usersList') active @endif">
                <a href="{{ route('users_list') }}"><i class="fa fa-circle-o"></i> All Users</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'createUser') active @endif">
                <a href="{{ route('crte_user') }}"><i class="fa fa-circle-o"></i> Create User</a>
              </li>
              {{--<li class="@if(isset($childMenu) && $childMenu == 'usrRoles') active @endif">
                <a href="{{ route('allRoles') }}"><i class="fa fa-circle-o"></i> Roles & Permissions</a>
              </li>--}}
            </ul>
          </li>

          <li class="treeview @if(isset($parentMenu) && $parentMenu == 'menuMan') active @endif">
            <a href="#">
              <i class="fa fa-bars" aria-hidden="true"></i> <span>Menu Management</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class="@if(isset($childMenu) && $childMenu == 'allMenu') active @endif">
                <a href="{{ route('allMnus') }}"><i class="fa fa-circle-o"></i> All Menus</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'naviMenu') active @endif">
                <a href="{{ route('NaviMan') }}"><i class="fa fa-circle-o"></i> Navigations</a>
              </li>
            </ul>
          </li>

          {{--<li class="@if(isset($childMenu) && $childMenu == 'regoSett') active @endif">
            <a href="{{ route('regio_page') }}"><i class="fa fa-circle-o"></i> Regional Settings</a>
          </li>--}}

          <li class="@if(isset($oneMenu) && $oneMenu == 'dbbkp') active @endif">
            <a href="{{ route('dbbacks') }}">
              <i class="fa fa-database"></i> <span>Database Backups</span>
            </a>
          </li>
          
        </ul>
      </li>




      <li class="treeview @if(isset($parentMenu) && $parentMenu == 'prodManagement') active @endif">
        <a href="#">
          <i class="fa fa-gift" aria-hidden="true"></i> <span>Product Content</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="@if(isset($childMenu) && $childMenu == 'prodList') active @endif">
            <a href="{{ route('allProds') }}"><i class="fa fa-circle-o"></i> All Products</a>
          </li>
          <li class="@if(isset($childMenu) && $childMenu == 'prodAdd') active @endif">
            <a href="{{ route('addProd') }}"><i class="fa fa-circle-o"></i> Add New Product</a>
          </li>
          <li class="@if(isset($childMenu) && $childMenu == 'prodCats') active @endif">
            <a href="{{ route('prodCats') }}"><i class="fa fa-circle-o"></i> Categories</a>
          </li>
          <li class="@if(isset($childMenu) && $childMenu == 'prodAddCats') active @endif">
            <a href="{{ route('prodCrteCat') }}"><i class="fa fa-circle-o"></i> Add Category</a>
          </li>
        </ul>
      </li>




      <li class="treeview @if(isset($GparentMenu) && $GparentMenu == 'contentManagement') active @endif">
        <a href="#">
          <i class="fa fa-bars" aria-hidden="true"></i> <span>Content Management</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          
          <li class="treeview @if(isset($parentMenu) && $parentMenu == 'homepage') active @endif">
            <a href="#">
              <i class="fa fa-home" aria-hidden="true"></i> <span>Home Page</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class="@if(isset($childMenu) && $childMenu == 'homeCont') active @endif">
                <a href="{{ route('home.cont') }}"><i class="fa fa-circle-o" aria-hidden="true"></i> Home Page Content</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'hMineralProcessing') active @endif">
                <a href="{{ route('hmps') }}"><i class="fa fa-circle-o" aria-hidden="true"></i> Mineral Processing</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'hMineral') active @endif">
                <a href="{{ route('mina') }}"><i class="fa fa-circle-o" aria-hidden="true"></i> Minerals</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'banner') active @endif">
                <a href="{{ route('bannList') }}"><i class="fa fa-circle-o"></i> Home Page Banners</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'homeMap') active @endif">
                <a href="{{ route('home.map') }}"><i class="fa fa-circle-o" aria-hidden="true"></i> Home Page Map</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'homeLogo') active @endif">
                <a href="{{ route('home.logo') }}"><i class="fa fa-circle-o" aria-hidden="true"></i> Home Page Logo</a>
              </li>
            </ul>
          </li>

          <li class="treeview @if(isset($parentMenu) && $parentMenu == 'contManagement') active @endif">
            <a href="#">
              <i class="fa fa-newspaper-o" aria-hidden="true"></i> <span>Contents</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class="@if(isset($childMenu) && $childMenu == 'allContType') active @endif">
                <a href="{{ route('allContTyps') }}"><i class="fa fa-circle-o"></i> All Content Types</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'addContType') active @endif">
                <a href="{{ route('addContTyp') }}"><i class="fa fa-circle-o"></i> Add Content Type</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'mngConts') active @endif">
                <a href="{{ route('typeList') }}"><i class="fa fa-circle-o"></i> Manage Contents</a>
              </li>
            </ul>
          </li>

          <li class="treeview @if(isset($parentMenu) && $parentMenu == 'IndustryManagement') active @endif">
            <a href="#">
              <i class="fa fa-university" aria-hidden="true"></i> <span>Industry Management</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class="@if(isset($childMenu) && $childMenu == 'indusList') active @endif">
                <a href="{{ route('allIndus') }}"><i class="fa fa-circle-o"></i> All Industries</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'addIndus') active @endif">
                <a href="{{ route('addIndus') }}"><i class="fa fa-circle-o"></i> Add New Industry</a>
              </li>
            </ul>
          </li>


          <li class="treeview @if(isset($parentMenu) && $parentMenu == 'indusFsheet') active @endif">
            <a href="#">
              <i class="fa fa-puzzle-piece" aria-hidden="true"></i> <span>Industry Flowsheet</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class="@if(isset($childMenu) && $childMenu == 'allFsheets') active @endif">
                <a href="{{ route('allFSs') }}"><i class="fa fa-circle-o"></i> All Flowsheets</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'addFsheet') active @endif">
                <a href="{{ route('crteFS') }}"><i class="fa fa-circle-o"></i> Add New Flowsheet</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'FsheetCats') active @endif">
                <a href="{{ route('allFSc') }}"><i class="fa fa-circle-o"></i> All Categories</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'FsheetAddCat') active @endif">
                <a href="{{ route('addFSc') }}"><i class="fa fa-circle-o"></i> Add Category</a>
              </li>
            </ul>
          </li>


          <li class="treeview @if(isset($parentMenu) && $parentMenu == 'distributorManagement') active @endif">
            <a href="#">
              <i class="fa fa-cubes" aria-hidden="true"></i> <span>Distributor Management</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class="@if(isset($childMenu) && $childMenu == 'distributorContns') active @endif">
                <a href="{{ route('allDistribConts') }}"><i class="fa fa-circle-o"></i> All Branches</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'distributorAddContns') active @endif">
                <a href="{{ route('crteDistribCont') }}"><i class="fa fa-circle-o"></i> Add New Branch</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'distributorList') active @endif">
                <a href="{{ route('allDistrib') }}"><i class="fa fa-circle-o"></i> All Countries</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'distributorAdd') active @endif">
                <a href="{{ route('crteDistrib') }}"><i class="fa fa-circle-o"></i> Add New Country</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'distributorCats') active @endif">
                <a href="{{ route('allDistribCats') }}"><i class="fa fa-circle-o"></i> All Continents</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'distributorAddCats') active @endif">
                <a href="{{ route('crteDistribCats') }}"><i class="fa fa-circle-o"></i> Add New Continent</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'disExCont') active @endif">
                <a href="{{ route('dis.extra_cont') }}"><i class="fa fa-circle-o"></i> Extra Content</a>
              </li>
              {{--<li class="@if(isset($childMenu) && $childMenu == 'allloc') active @endif">
                <a href="{{ route('distr.allloc') }}"><i class="fa fa-circle-o"></i> All Locations</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'disloc') active @endif">
                <a href="{{ route('distr.addloc') }}"><i class="fa fa-circle-o"></i> Add Location</a>
              </li>--}}
            </ul>
          </li>


          <li class="treeview @if(isset($parentMenu) && $parentMenu == 'articleManagement') active @endif">
            <a href="#">
              <i class="fa fa-bullhorn" aria-hidden="true"></i> <span>News, Articles & Events</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class="@if(isset($childMenu) && $childMenu == 'allArticle') active @endif">
                <a href="{{ route('allArts') }}"><i class="fa fa-circle-o"></i> All Articles</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'addArticle') active @endif">
                <a href="{{ route('addArt') }}"><i class="fa fa-circle-o"></i> Add New Article</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'allArtCats') active @endif">
                <a href="{{ route('allArtCats') }}"><i class="fa fa-circle-o"></i> All Categories</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'addArtCat') active @endif">
                <a href="{{ route('addArtCats') }}"><i class="fa fa-circle-o"></i> Add New Category</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'artExCont') active @endif">
                <a href="{{ route('art.extra_cont') }}"><i class="fa fa-circle-o"></i> Extra Content</a>
              </li>
            </ul>
          </li>

          <li class="treeview @if(isset($parentMenu) && $parentMenu == 'peopleManagement') active @endif">
            <a href="#">
              <i class="fa fa-id-card-o" aria-hidden="true"></i> <span>Profile Management</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class="@if(isset($childMenu) && $childMenu == 'profileList') active @endif">
                <a href="{{ route('allProfiles') }}"><i class="fa fa-circle-o"></i> All Profiles</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'profileAdd') active @endif">
                <a href="{{ route('addProfile') }}"><i class="fa fa-circle-o"></i> Add New Profile</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'profileCats') active @endif">
                <a href="{{ route('allProfileCats') }}"><i class="fa fa-circle-o"></i> All Categories</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'profileAddCat') active @endif">
                <a href="{{ route('addProfileCat') }}"><i class="fa fa-circle-o"></i> Add New Category</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'profExCont') active @endif">
                <a href="{{ route('prof.extra_cont') }}"><i class="fa fa-circle-o"></i> Extra Content</a>
              </li>
            </ul>
          </li>


          <li class="treeview @if(isset($parentMenu) && $parentMenu == 'Webinar') active @endif">
            <a href="#">
              <i class="fa fa-university" aria-hidden="true"></i> <span>Webinar Page</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class="@if(isset($childMenu) && $childMenu == 'webinar') active @endif">
                <a href="{{ route('allWbContent') }}"><i class="fa fa-circle-o"></i>Webinar List Page</a>
              </li>
              
            </ul>
          </li>


          {{--<li class="treeview @if(isset($parentMenu) && $parentMenu == 'eventManagement') active @endif">
            <a href="#">
              <i class="fa fa-calendar" aria-hidden="true"></i> <span>Event Management</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class="@if(isset($childMenu) && $childMenu == 'calView') active @endif">
                <a href="{{ route('evts_cal') }}"><i class="fa fa-circle-o"></i> Event Calendar</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'listView') active @endif">
                <a href="{{ route('evts_lst') }}"><i class="fa fa-circle-o"></i> Event List</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'crteEvent') active @endif">
                <a href="{{ route('evts_crte') }}"><i class="fa fa-circle-o"></i> Create Event</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'catsEvent') active @endif">
                <a href="{{ route('evt_cats') }}"><i class="fa fa-circle-o"></i> Event Categories</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'crteEvtCat') active @endif">
                <a href="{{ route('evt_crte_cat') }}"><i class="fa fa-circle-o"></i> Create Category</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'evtExCont') active @endif">
                <a href="{{ route('evt.extra_cont') }}"><i class="fa fa-circle-o"></i> Extra Content</a>
              </li>
            </ul>
          </li>--}}
          
        </ul>
      </li>



      <li class="treeview @if(isset($parentMenu) && $parentMenu == 'media') active @endif">
        <a href="#">
          <i class="fa fa-bars" aria-hidden="true"></i> <span>Resources</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="treeview @if(isset($subMenu) && $subMenu == 'image') active @endif">
            <a href="#"><i class="fa fa-picture-o"></i> Images
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class="@if(isset($childMenu) && $childMenu == 'allImgs') active @endif">
                <a href="{{ route('media_all_imgs') }}"><i class="fa fa-circle-o"></i> All Images</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'addImg') active @endif">
                <a href="{{ route('media_img_add') }}"><i class="fa fa-circle-o"></i> Add New Image</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'mngImgCats') active @endif">
                <a href="{{ route('media_all_img_cats') }}"><i class="fa fa-circle-o"></i> Manage Categories</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'imgExCont') active @endif">
                <a href="{{ route('media.img_extra_cont') }}"><i class="fa fa-circle-o"></i> Extra Content</a>
              </li>
              <!--li class="treeview">
                <a href="#"><i class="fa fa-circle-o"></i> Level Two
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                  <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                </ul>
              </li-->
            </ul>
          </li>
          <li class="treeview @if(isset($subMenu) && $subMenu == 'video') active @endif">
            <a href="#"><i class="fa fa-video-camera"></i> Video
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class="@if(isset($childMenu) && $childMenu == 'allVids') active @endif">
                <a href="{{ route('allVideos') }}"><i class="fa fa-circle-o"></i> All Videos</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'addVid') active @endif">
                <a href="{{ route('addVideo') }}"><i class="fa fa-circle-o"></i> Add New Video</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'vidCats') active @endif">
                <a href="{{ route('videoCats') }}"><i class="fa fa-circle-o"></i> Manage Categories</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'vidExCont') active @endif">
                <a href="{{ route('media.vid_extra_cont') }}"><i class="fa fa-circle-o"></i> Extra Content</a>
              </li>
            </ul>
          </li>
          <li class="treeview @if(isset($subMenu) && $subMenu == 'file') active @endif">
            <a href="#"><i class="fa fa-file-text"></i> Files
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class="@if(isset($childMenu) && $childMenu == 'allFls') active @endif">
                <a href="{{ route('allFiles') }}"><i class="fa fa-circle-o"></i> All Files</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'addFl') active @endif">
                <a href="{{ route('addFile') }}"><i class="fa fa-circle-o"></i> Add New File</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'flCats') active @endif">
                <a href="{{ route('allFlCats') }}"><i class="fa fa-circle-o"></i> Manage Categories</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'filExCont') active @endif">
                <a href="{{ route('media.fil_extra_cont') }}"><i class="fa fa-circle-o"></i> Extra Content</a>
              </li>
            </ul>
          </li>
          <li class="treeview @if(isset($subMenu) && $subMenu == 'techResManagement') active @endif">
            <a href="#">
              <i class="fa fa-diamond" aria-hidden="true"></i> <span>Technical Resources</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class="@if(isset($childMenu) && $childMenu == 'allTechRes') active @endif">
                <a href="{{ route('allResource') }}"><i class="fa fa-circle-o"></i> All Resources</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'addTechRes') active @endif">
                <a href="{{ route('addResource') }}"><i class="fa fa-circle-o"></i> Add New Resource</a>
              </li>
              {{--<li class="@if(isset($childMenu) && $childMenu == 'allPersona') active @endif">
                <a href="{{ route('allPersonas') }}"><i class="fa fa-circle-o"></i> All Categories</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'addPersona') active @endif">
                <a href="{{ route('addPersona') }}"><i class="fa fa-circle-o"></i> Add New Category</a>
              </li>--}}
              <li class="@if(isset($childMenu) && $childMenu == 'tresExCont') active @endif">
                <a href="{{ route('tres.extra_cont') }}"><i class="fa fa-circle-o"></i> Extra Content</a>
              </li>
            </ul>
          </li>
          {{--<li class="treeview @if(isset($subMenu) && $subMenu == 'career') active @endif">
            <a href="#">
              <i class="fa fa-graduation-cap" aria-hidden="true"></i> <span>Career Management</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class="@if(isset($childMenu) && $childMenu == 'allJobs') active @endif">
                <a href="{{ route('allJobs') }}"><i class="fa fa-circle-o"></i> All Jobs</a>
              </li>
              <li class="@if(isset($childMenu) && $childMenu == 'addJob') active @endif">
                <a href="{{ route('addJob') }}"><i class="fa fa-circle-o"></i> Add New Job</a>
              </li>
            </ul>
          </li>--}}
        </ul>
      </li>


      
      <!--li class="header">USER MANAGEMENT</li-->
      <li class="treeview @if(isset($parentMenu) && $parentMenu == 'FrmB') active @endif">
        <a href="#">
          <i class="fa fa-file-text" aria-hidden="true"></i> <span>Form Builder</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="@if(isset($childMenu) && $childMenu == 'frms') active @endif">
            <a href="{{ route('frms') }}"><i class="fa fa-circle-o"></i> All Forms</a>
          </li>
          <li class="@if(isset($childMenu) && $childMenu == 'frm_crte') active @endif">
            <a href="{{ route('crte_frm') }}"><i class="fa fa-circle-o"></i> Create New Form</a>
          </li>
          <li class="@if(isset($childMenu) && $childMenu == 'capStt') active @endif">
            <a href="{{ route('frm_sett') }}"><i class="fa fa-circle-o"></i> Captcha Settings</a>
          </li>
          <li class="@if(isset($childMenu) && $childMenu == 'frmCats') active @endif">
            <a href="{{ route('frmCats') }}"><i class="fa fa-circle-o"></i> Form Categories</a>
          </li>
          <li class="@if(isset($childMenu) && $childMenu == 'frmCats_crte') active @endif">
            <a href="{{ route('frmCats_crte') }}"><i class="fa fa-circle-o"></i> Create Category</a>
          </li>
        </ul>
      </li>
      
      <!--li class="header">Resuable Content</li-->
      <li class="treeview @if(isset($parentMenu) && $parentMenu == 'rsbC') active @endif">
        <a href="#">
          <i class="fa fa-recycle" aria-hidden="true"></i> <span>Reusable Content</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="@if(isset($childMenu) && $childMenu == 'rsbC_list') active @endif">
            <a href="{{ route('rsbC_list') }}"><i class="fa fa-circle-o"></i> All Contents</a>
          </li>
          <li class="@if(isset($childMenu) && $childMenu == 'rsbC_crte') active @endif">
            <a href="{{ route('rsbC_crte') }}"><i class="fa fa-circle-o"></i> Create New Content</a>
          </li>
          <li class="@if(isset($childMenu) && $childMenu == 'prod_content') active @endif">
            <a href="{{ route('pbox_rlist') }}"><i class="fa fa-circle-o"></i> ProductBox Content</a>
          </li>
        </ul>
      </li>
      
      <!--li class="header">MENU</li-->
      <li class="treeview @if(isset($parentMenu) && $parentMenu == 'landPage') active @endif">
        <a href="#">
          <i class="fa fa-heart" aria-hidden="true"></i> <span>Landing Pages</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="@if(isset($childMenu) && $childMenu == 'landList') active @endif">
            <a href="{{ route('land.list') }}"><i class="fa fa-circle-o"></i> All Landing Pages</a>
          </li>
          <li class="@if(isset($childMenu) && $childMenu == 'landNew') active @endif">
            <a href="{{ route('land.new') }}"><i class="fa fa-circle-o"></i> Create New</a>
          </li>
        </ul>
      </li>


      <!--li class="header">Redirection</li-->
      <li class="treeview @if(isset($parentMenu) && $parentMenu == 'redirect') active @endif">
        <a href="#">
          <i class="fa fa-retweet" aria-hidden="true"></i> <span>Page Redirection</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="@if(isset($childMenu) && $childMenu == 'r404') active @endif">
            <a href="{{ route('r404') }}"><i class="fa fa-circle-o"></i> 404 Redirect</a>
          </li>
          <li class="@if(isset($childMenu) && $childMenu == 'r301') active @endif">
            <a href="{{ route('r301') }}"><i class="fa fa-circle-o"></i> 301 Redirect</a>
          </li>
        </ul>
      </li>
      
      
       <!--li class="header">Redirection</li-->
       <li class="treeview @if(isset($parentMenu) && $parentMenu == 'Traffic') active @endif">
        <a href="#">
          <i class="fa fa-retweet" aria-hidden="true"></i> <span>Traffic Source Management</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
        <li class="@if(isset($childMenu) && $childMenu == 'allSrc') active @endif">
            <a href="{{ route('allSrc') }}"><i class="fa fa-circle-o"></i> Source Type Management</a>
          </li>

          <li class="@if(isset($childMenu) && $childMenu == 'allCp') active @endif">
            <a href="{{ route('allCp') }}"><i class="fa fa-circle-o"></i>   Campaign Management</a>
          </li>

          <li class="@if(isset($childMenu) && $childMenu == 'list_referral') active @endif">
            <a href="{{ route('list_referral') }}"><i class="fa fa-circle-o"></i> List Referral</a>
          </li>
          
        </ul>
      </li>


      <li class="treeview @if(isset($parentMenu) && $parentMenu == 'Webiner') active @endif">
        <a href="#">
          <i class="fa fa-retweet" aria-hidden="true"></i> <span>Webinar Management</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
        <li class="@if(isset($childMenu) && $childMenu == 'allWbCt') active @endif">
            <a href="{{ route('allWbId') }}"><i class="fa fa-circle-o"></i> Webinar Industry</a>
          </li>
        <li class="@if(isset($childMenu) && $childMenu == 'allWbCt') active @endif">
            <a href="{{ route('allWbTp') }}"><i class="fa fa-circle-o"></i> Webinar Topics</a>
          </li>
        <li class="@if(isset($childMenu) && $childMenu == 'allWbCt') active @endif">
            <a href="{{ route('allWbCt') }}"><i class="fa fa-circle-o"></i> Webinar Product</a>
          </li>

          <li class="@if(isset($childMenu) && $childMenu == 'allWb') active @endif">
            <a href="{{ route('allWb') }}"><i class="fa fa-circle-o"></i>   List Webinar</a>
          </li>
  
        </ul>
      </li>
      <li class="treeview @if(isset($parentMenu) && $parentMenu == 'eventManaget') active @endif"
  
      >
        <a href="#">
          <i class="fa fa-retweet" aria-hidden="true"></i> <span>Event Type</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
       
        <li class="@if(isset($childMenu) && $childMenu == 'allEventCtt') active @endif">
            <a href="{{route('allEventManagementType')}}"><i class="fa fa-circle-o"></i> Event Types</a>
          </li>

          <li class="@if(isset($childMenu) && $childMenu == 'addEventCtt') active @endif">
            <a href="{{ route('addEventManagementType') }}"><i class="fa fa-circle-o"></i> Add Event Type</a>
          </li>
     
        </ul>
      </li>
      <li class="treeview @if(isset($parentMenu) && $parentMenu == 'eventManage') active @endif"
  
      >
        <a href="#">
          <i class="fa fa-retweet" aria-hidden="true"></i> <span>Event Management</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
       
        <li class="@if(isset($childMenu) && $childMenu == 'allEventCt') active @endif">
            <a href="{{route('alleventManagement')}}"><i class="fa fa-circle-o"></i> All Events</a>
          </li>

          <li class="@if(isset($childMenu) && $childMenu == 'addEventCt') active @endif">
            <a href="{{ route('addEventManagement') }}"><i class="fa fa-circle-o"></i> Add Event</a>
          </li>
     
        </ul>
      </li>
      
      <!--li class="header">SETTINGS</li-->
      <li class="treeview @if(isset($parentMenu) && $parentMenu == 'settings') active @endif">
        <a href="#">
          <i class="fa fa-cogs" aria-hidden="true"></i> <span>Settings</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="@if(isset($childMenu) && $childMenu == 'genSett') active @endif">
            <a href="{{ route('gen_sett') }}"><i class="fa fa-circle-o"></i> General Settings</a>
          </li>
          
          <li class="@if(isset($childMenu) && $childMenu == 'soLinks') active @endif">
            <a href="{{ route('social_links') }}"><i class="fa fa-circle-o"></i> Social Links</a>
          </li>
          
          <li class="@if(isset($childMenu) && $childMenu == 'emTemp') active @endif">
            <a href="{{ route('empTemp_lists') }}"><i class="fa fa-circle-o"></i> Email Templates</a>
          </li>
          
          <li class="@if(isset($childMenu) && $childMenu == 'anaScripts') active @endif">
            <a href="{{ route('anaLyticScripts') }}"><i class="fa fa-circle-o"></i> Analytics and Scripts</a>
          </li>
          
          <li class="@if(isset($childMenu) && $childMenu == 'lng') active @endif">
            <a href="{{ route('langList') }}"><i class="fa fa-circle-o"></i> Language Settings</a>
          </li>

          <li class="@if(isset($childMenu) && $childMenu == 'profile') active @endif">
            <a href="{{ route('usr_profile') }}"><i class="fa fa-circle-o"></i> My Profile</a>
          </li>
          <li class="@if(isset($childMenu) && $childMenu == 'cngPwd') active @endif">
            <a href="{{ route('cng_pwd') }}"><i class="fa fa-circle-o"></i> Change Password</a>
          </li>




        </ul>
      </li>
      
      <!--li><a href="https://adminlte.io/docs"><i class="fa fa-book"></i> <span>Documentation</span></a></li>
      <li class="header">LABELS</li>
      <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>Important</span></a></li>
      <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> <span>Warning</span></a></li>
      <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>Information</span></a></li-->
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>

<!-- =============================================== -->