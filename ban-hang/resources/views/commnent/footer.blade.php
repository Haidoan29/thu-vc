   <footer class="footer">
       <div class="footer-container">

           <!-- Cột 1 - Thông tin liên hệ -->
           <div class="footer-col">
               <h3>Thông tin liên hệ</h3>
               <p><strong>CÔNG TY TNHH PHẦN MỀM ABC</strong></p>

               <p>Địa chỉ: </p>
               <p>Điện thoại: 1900 6680 - 0901191616</p>
               <p>Email: abc@gmail.com</p>

               <div class="social-icons">
                   <a href="#"><i class="fab fa-facebook-f"></i></a>
                   <a href="#"><i class="fab fa-twitter"></i></a>
                   <a href="#"><i class="fab fa-google-plus-g"></i></a>
                   <a href="#"><i class="fab fa-instagram"></i></a>
                   <a href="#"><i class="fab fa-youtube"></i></a>
               </div>
           </div>

           <!-- Cột 2 - Liên kết -->
           <div class="footer-col">
               <h3 class="font-semibold mb-4 text-lg">Liên kết</h3>
               <ul class="space-y-3 text-gray-700">
                   <li>
                       <a href="/" class="hover:text-green-500 transition-colors duration-200">
                           Trang chủ
                       </a>
                   </li>

                   <li>
                       <a class="font-semibold" href="{{ route('products.getall') }}">Sản phẩm:</a>
                       <ul class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-2 gap-2 mt-2">
                           @foreach($menuCategories as $c)
                           <li>
                               <a href="{{ route('category.products', $c->_id) }}"
                                   class="block text-gray-700 hover:text-green-500 transition-colors duration-200">
                                   {{ $c->name }}
                               </a>
                           </li>
                           @endforeach
                       </ul>
                   </li>

                   <li>
                       <a href="/tin-tuc" class="hover:text-green-500 transition-colors duration-200">
                           Tin tức
                       </a>
                   </li>
                   <li>
                       <a href="/lien-he" class="hover:text-green-500 transition-colors duration-200">
                           Liên hệ
                       </a>
                   </li>
               </ul>
           </div>



           <!-- Cột 3 - Chính sách -->
           <div class="footer-col">
               <h3>Chính sách</h3>
               <ul>
                   <li><a href="#">Hướng dẫn mua hàng</a></li>
                   <li><a href="#">Hướng dẫn đổi trả hàng</a></li>
                   <li><a href="#">Hướng dẫn thanh toán</a></li>
                   <li><a href="#">Chính sách giao hàng</a></li>
                   <li><a href="#">Chính sách bảo mật</a></li>
                   <li><a href="#">Chính sách khuyến mại</a></li>
               </ul>
           </div>

           <!-- Cột 4 - Đăng ký nhận tin -->
           <div class="footer-col">
               <h3>Đăng ký ngay</h3>
               <p>Đăng ký để nhận những sản phẩm và tin tức mới nhất cập nhật hàng ngày nhanh nhất.</p>

               <form class="subscribe-form">
                   <input type="email" placeholder="Email của bạn">
                   <button type="submit">Đăng ký</button>
               </form>
           </div>
       </div>
   </footer>