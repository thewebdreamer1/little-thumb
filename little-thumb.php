/**
 * Create a 200x200 version of an image given its system path.
 * @param   string     $file_path           System file path
 * @return  int        $parent_post_id      Parent post ID
 * @return  void
 */
function create_little_thumb( $file_path ) {
  $image_editor = wp_get_image_editor( $file_path );

  if ( ! is_wp_error( $image_editor ) ) {
    $basename = basename( $file_path );
    $imagetype = end( explode( '/', getimagesize( $file_path )['mime'] ) );

    $without_extension = pathinfo($basename, PATHINFO_FILENAME);
    $extension = pathinfo($basename, PATHINFO_EXTENSION);
    if($extension == 'pdf') {
      $filename = $without_extension.'-little-thumb.pdf';
    } else {
      $filename = $without_extension.'-little-thumb.'.$imagetype;
    }

    $uploaddir = wp_upload_dir();
    $uploadfile = $uploaddir['path'] . '/' . $filename;
    $uploadurl = $uploaddir['url'] . '/' . $filename;

    if ( ! file_exists( $uploadfile ) ) {
      $image_editor->resize( 200, 200, true );
      $image_editor->save( $uploadfile );
      $protocol_and_domain = ( isset( $_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
      $trimmed_path = '/content/uploads'.explode( '/content/uploads', $image_editor->generate_filename() )[1];
      $path_end = str_replace( 'little-thumb-200x200', 'little-thumb' , $trimmed_path );
      $full_url = $protocol_and_domain.$path_end;

      return $full_url;
    } else {
      return $uploadurl;
    }
  }

  return 0;
}
