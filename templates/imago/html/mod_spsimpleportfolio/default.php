<?php
/**
 * @package     SP Simple Portfolio
 * @subpackage  mod_spsimpleportfolio
 *
 * @copyright   Copyright (C) 2010 - 2014 JoomShaper. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

defined('_JEXEC') or die;
jimport( 'joomla.filesystem.file' );
$layout_type = $params->get('layout_type', 'default');

$i = 0;
//Sizes
$sizes = array(
	'600x400',
	'600x800',
	'600x600',

	'600x800',
	'600x400',
	'600x600',

	'600x600',
	'600x400',
	'600x800',

	'600x600',
	'600x800',
	'600x400'
	);

//Params

$params1 	= JComponentHelper::getParams('com_spsimpleportfolio');
$square 	= strtolower( $params1->get('square', '600x600') );
$rectangle 	= strtolower( $params1->get('rectangle', '600x400') );
$tower 		= strtolower( $params1->get('tower', '600x800') );

?>


<div id="mod-sp-simpleportfolio" class="sp-simpleportfolio sp-simpleportfolio-view-items layout-<?php echo str_replace('_', '-', $layout_type); ?> <?php echo $moduleclass_sfx; ?>">

	<?php if($params->get('show_filter', 1)) { ?>
		<div class="sp-simpleportfolio-filter">
			<ul>
				<li class="active" data-group="all"><a href="#"><?php echo JText::_('MOD_SPSIMPLEPORTFOLIO_SHOW_ALL'); ?></a></li>
				<?php
					$filters = SpsimpleportfolioHelper::getTagList( $items );
					foreach ($filters as $filter) {
						?>
							<li data-group="<?php echo $filter->alias; ?>"><a href="#"><?php echo $filter->title; ?></a></li>
						<?php
					}	
				?>
			</ul>
		</div>
	<?php } ?>

	<?php
		//Videos
		foreach ($items as $item) {

			if($item->video) {
				$video = parse_url($item->video);

				switch($video['host']) {
					case 'youtu.be':
					$video_id 	= trim($video['path'],'/');
					$video_src 	= '//www.youtube.com/embed/' . $video_id;
					break;

					case 'www.youtube.com':
					case 'youtube.com':
					parse_str($video['query'], $query);
					$video_id 	= $query['v'];
					$video_src 	= '//www.youtube.com/embed/' . $video_id;
					break;

					case 'vimeo.com':
					case 'www.vimeo.com':
					$video_id 	= trim($video['path'],'/');
					$video_src 	= "//player.vimeo.com/video/" . $video_id;
				}

				echo '<iframe class="sp-simpleportfolio-lightbox" src="'. $video_src .'" width="500" height="281" id="sp-simpleportfolio-video'.$item->spsimpleportfolio_item_id.'" style="border:none;" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
			}
		}
	?>

	<div class="sp-simpleportfolio-items sp-simpleportfolio-columns-<?php echo $params->get('columns', 3); ?>">
		<?php foreach ($items as $item) { ?>
			
			<?php
			$tags = SpsimpleportfolioHelper::getTags( $item->spsimpleportfolio_tag_id );
			$newtags = array();
			$filter = '';
			$groups = array();
			foreach ($tags as $tag) {
				$newtags[] 	 = $tag->title;
				$filter 	.= ' ' . $tag->alias;
				$groups[] 	.= '"' . $tag->alias . '"';
			}

			$groups = implode(',', $groups);

			?>

			<div class="sp-simpleportfolio-item" data-groups='[<?php echo $groups; ?>]'>
				<?php $item->url = JRoute::_('index.php?option=com_spsimpleportfolio&view=item&id='.$item->spsimpleportfolio_item_id.':'.$item->alias); ?>
				
				<div class="sp-simpleportfolio-overlay-wrapper clearfix">
					
					<?php if($item->video) { ?>
						<span class="sp-simpleportfolio-icon-video"></span>
					<?php } ?>

					<?php if($params->get('thumbnail_type', 'masonry') == 'masonry') { ?>
						<img class="sp-simpleportfolio-img" src="<?php echo JURI::base(true) . '/images/spsimpleportfolio/' . $item->alias . '/' . JFile::stripExt(JFile::getName($item->image)) . '_' . $sizes[$i] . '.' . JFile::getExt($item->image); ?>" alt="<?php echo $item->title; ?>">
					<?php } else if($params->get('thumbnail_type', 'masonry') == 'rectangular') { ?>
						<img class="sp-simpleportfolio-img" src="<?php echo JURI::base(true) . '/images/spsimpleportfolio/' . $item->alias . '/' . JFile::stripExt(JFile::getName($item->image)) . '_'.$rectangle.'.' . JFile::getExt($item->image); ?>" alt="<?php echo $item->title; ?>">
					<?php } else { ?>
						<img class="sp-simpleportfolio-img" src="<?php echo JURI::base(true) . '/images/spsimpleportfolio/' . $item->alias . '/' . JFile::stripExt(JFile::getName($item->image)) . '_600x600.' . JFile::getExt($item->image); ?>" alt="<?php echo $item->title; ?>">
					<?php } ?>

					<div class="sp-simpleportfolio-overlay">
						<div class="sp-vertical-middle">
							<div>
								<div class="sp-simpleportfolio-btns">
									<?php if( $item->video ) { ?>
										<a class="btn-zoom" href="#" data-featherlight="#sp-simpleportfolio-video<?php echo $item->spsimpleportfolio_item_id; ?>"><?php echo JText::_('COM_SPSIMPLEPORTFOLIO_WATCH'); ?></a>
									<?php } else { ?>
										<a class="btn-zoom" href="<?php echo JURI::base(true) . '/images/showcase/' . JFile::stripExt(JFile::getName($item->image)) . '.' . JFile::getExt($item->image); ?>" data-featherlight="image"><i class="fa fa-arrows-alt"></i></a>
									<?php } ?>
									<a class="btn-view" href="<?php echo $item->url; ?>"><i class="fa fa-link"></i></a>
								</div>
								<?php if($layout_type!='default') { ?>
								<h3 class="sp-simpleportfolio-title">
									<a href="<?php echo $item->url; ?>">
										<?php echo $item->title; ?>
									</a>
								</h3>
								<div class="sp-simpleportfolio-tags">
									<?php echo implode(', ', $newtags); ?>
								</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
				
				<?php if($layout_type=='default') { ?>
					<div class="sp-simpleportfolio-info">
						<h3 class="sp-simpleportfolio-title">
							<a href="<?php echo $item->url; ?>">
								<?php echo $item->title; ?>
							</a>
						</h3>
						<div class="sp-simpleportfolio-tags">
							<?php echo implode(', ', $newtags); ?>
						</div>
					</div>
				<?php } ?>

			</div>

			<?php
			$i++;
			if($i==11) {
				$i = 0;
			}
			?>

		<?php } ?>
	</div>

</div>
