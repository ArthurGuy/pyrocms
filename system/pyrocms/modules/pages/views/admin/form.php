<?php if ($this->method == 'create'): ?>
	<h3><?php echo lang('pages.create_title');?></h3>
<?php else: ?>
	<h3><?php echo sprintf(lang('pages.edit_title'), $page->title);?></h3>
<?php endif; ?>


<?php echo form_open(uri_string(), 'class="crud"'); ?>
<?php echo form_hidden('parent_id', (@$page->parent_id == '')? 0 : $page->parent_id); ?>

<div class="tabs">

	<ul class="tab-menu">
		<li><a href="#page-content"><span><?php echo lang('pages.content_label');?></span></a></li>
		<li><a href="#page-attachments"><span><?php echo lang('files_attached.attachments_label');?></span></a></li>
		<li><a href="#page-meta"><span><?php echo lang('pages.meta_label');?></span></a></li>
		<li><a href="#page-design"><span><?php echo lang('pages.design_label');?></span></a></li>
		<li><a href="#page-script"><span><?php echo lang('pages.script_label');?></span></a></li>
		<li><a href="#page-options"><span><?php echo lang('pages.options_label');?></span></a></li>
	</ul>

	<?php alternator(); ?>

	<!-- Content tab -->
	<div id="page-content">
		<ul>
			<li class="<?php echo alternator('even', ''); ?>">
				<label for="title"><?php echo lang('pages.title_label');?></label>
				<?php echo form_input('title', $page->title, 'maxlength="60"'); ?>
				<span class="required-icon tooltip"><?php echo lang('required_label');?></span>
			</li>
			<li class="<?php echo alternator('even', ''); ?>">
				<label for="slug"><?php echo lang('pages.slug_label');?></label>
				<?php if ( ! empty($page->parent_id)): ?>
					<?php echo site_url($parent_page->uri); ?>/
				<?php else: ?>
					<?php echo site_url() . (config_item('index_page') ? '/' : ''); ?>
				<?php endif; ?>

				<?php if ($this->method == 'edit'): ?>
					<?php echo form_hidden('old_slug', $page->slug); ?>
				<?php endif; ?>

				<?php if (in_array($page->slug, array('home', '404'))): ?>
					<?php echo form_hidden('slug', $page->slug); ?>
					<?php echo form_input('', $page->slug, 'size="20" class="width-10" disabled="disabled"'); ?>
				<?php else: ?>
					<?php echo form_input('slug', $page->slug, 'size="20" class="width-10"'); ?>
					<span class="required-icon tooltip"><?php echo lang('required_label'); ?></span>
				<?php endif;?>

				<?php echo config_item('url_suffix'); ?>
			</li>
			<li class="<?php echo alternator('even', ''); ?>">
				<label for="category_id"><?php echo lang('pages.status_label'); ?></label>
				<?php echo form_dropdown('status', array('draft'=>lang('pages.draft_label'), 'live'=>lang('pages.live_label')), $page->status); ?>
			</li>
			<?php if ($this->method == 'create'): ?>
			<li class="<?php echo alternator('even', ''); ?>">
				<label for="navigation_group_id"><?php echo lang('pages.navigation_label');?></label>
				<?php echo form_dropdown('navigation_group_id', array(lang('select.none')) + $navigation_groups, $page->navigation_group_id); ?>
			</li>
			<?php endif; ?>
			<?php foreach ($page->chunks AS $chunk): ?>
				<li class="<?php echo alternator('even', ''); ?> page-chunk">
					<div class="float-left">
					<?php echo form_input('chunk_slug[]', $chunk->slug); ?>
					<?php echo form_dropdown('chunk_type[]', array(
						'html' => 'html',
						'wysiwyg-simple' => 'wysiwyg-simple',
						'wysiwyg-advanced' => 'wysiwyg-advanced',
					), $chunk->type); ?>
					</div>
					<div class="float-right">
						<a href="javascript:void(0)" class="remove-chunk"><?php echo lang('cp_remove') ?></a>
					</div>
					<br style="clear:both" />
					<?php echo form_textarea(array('id'=>$chunk->slug, 'name'=>'chunk_body[]', 'value' => $chunk->body, 'rows' => 50, 'class'=> $chunk->type)); ?>
				</li>
			<?php endforeach; ?>
			<li class="<?php echo alternator('even', ''); ?>">
				<a class="add-chunk" href="#"><?php echo lang('pages.add_page_chunk'); ?></a>
			</li>
		</ul>
	</div>

	<?php alternator(); ?>

	<!-- Attachments tab -->
	<div id="page-attachments">
		<ul>
			<li class="<?php echo alternator('even', ''); ?>">
				<label for="attachment_type" class="attachment"><?php echo lang('files_attached.attachment_type_label');?></label>
				<div class="spacer-right inline">
					<label><?php echo form_radio('attachment_type', 'file-browser') ?> <?php echo lang('files_attached.type_file_browser_label');?></label>
					<label><?php echo form_radio('attachment_type', 'file-upload') ?> <?php echo lang('files_attached.type_file_upload_label');?></label>
					<label><?php echo form_radio('attachment_type', 'link') ?> <?php echo lang('files_attached.type_link_label');?></label>
				</div>
				<?php echo form_hidden('attachments_key', $page->attachments_key); ?>
			</li>
			<li class="<?php echo alternator('even', ''); ?>">
				<p class="attachment_type_desc spacer-bottom-none">
					<?php echo lang('files_attached.attachment_type_desc'); ?>
				</p>
				<div id="attachment-file-browser" class="hidden">
					<h3><?php echo lang('files_attached.do_file_browser_label'); ?></h3>
					<ul class="spacer-bottom-none">
						<li class="even">
							<label for="file_browser"><?php echo lang('files_attached.pick_folder_label'); ?></label>
							<?php echo form_dropdown('attachment_file_folders', ($folders_tree ? (array('' => lang('select.pick')) + $folders_tree) : array('' => lang('select.none'))), '', 'id="file-browser-folders"'); ?>
							<div class="spacer-left button-icons inline">
								<?php echo anchor('admin/files/attachments/attach/file_browser', lang('files_attached.attach_file_browser_label'), 'title="' . lang('files_attached.attach_file_browser_desc') . '" class="button attach"'); ?>
							</div>
						</li>
						<li class="" id="file-browser-files">
							<label for="file_browser"><?php echo lang('files_attached.pick_file_label'); ?></label>
							<div class="container float-left">
								<?php //helper limited, need put many options attr.. :( ?>
								<?php //echo form_dropdown('attachment_file_contents', array(0 => lang('select.pick'), '{id}' => '{name}'), '', 'class=""'); ?>
								<select name="attachment_file_contents" class="" disabled="disabled">
									<option value=""><?php echo lang('select.pick'); ?></option>
									<option value="{id}" data-type="{type}" data-source="{source}" data-thumb="{thumb}" class="tmpl">{name}</option>
								</select>
							</div>
							<div class="file_preview inline"></div>
						</li>
					</ul>
				</div>
				<div id="attachment-file-upload" class="hidden">
					<label for="file_upload"><?php echo lang('files_attached.do_file_upload_label'); ?></label>
					<?php /* TODO: File upload form -> upload and save on folder dropbox */ ?>
				</div>
				<div id="attachment-link" class="hidden">
					<h3><?php echo lang('files_attached.do_link_label'); ?></h3>
					<ul class="spacer-bottom-none">
						<li class="even">
							<label for="attachment_link_url"><?php echo lang('files_attached.link_url_label');?></label>
							<?php echo form_input('attachment_link_url', 'http://'); ?>
							<span class="required-icon tooltip"><?php echo lang('required_label');?></span>
							<div class="spacer-left button-icons inline">
								<?php echo anchor('admin/files/attachments/attach/link', lang('files_attached.attach_link_label'), 'title="' . lang('files_attached.attach_link_desc') . '" class="button attach"'); ?>
							</div>
						</li>
						<li>
							<label for="attachment_link_title"><?php echo lang('files_attached.link_title_label');?></label>
							<?php echo form_input('attachment_link_title', ''); ?>
						</li>
						<li class="even">
							<label for="attachment_link_class"><?php echo lang('files_attached.link_class_label');?></label>
							<?php echo form_input('attachment_link_class', ''); ?>
						</li>
					</ul>
				</div>
			</li>
			<li class="<?php echo alternator('even', ''); ?>">
				<ul id="attachments-list" class="spacer-bottom-none">
				<?php if ($page->attachments): ?>
					<?php foreach ($page->attachments as $attachment): ?>
					<li class="attachment inline">
						<label>
							<?php echo form_checkbox('attachments[]', $attachment->id, $attachment->is_checked); ?>
							<span class="name type-<?php echo $attachment->type; ?>"><?php echo $attachment->title; ?></span>
						</label>
					</li>
					<?php endforeach; ?>
				<?php endif; ?>
					<li class="attachment inline tmpl">
						<label>
							<?php echo form_checkbox('attachments[]', '{id}', TRUE); ?>
							<span class="name type-<?php echo '{type}'; ?>">{title}</span>
						</label>
					</li>
					<li class="empty<?php echo $page->attachments ? ' hidden': ''; ?>"><p class="spacer-bottom-none"><?php echo lang('files_attached.no_attachments_desc'); ?></p></li>
				</ul>
			</li>
		</ul>
	</div>

	<?php alternator(); ?>

	<!-- Meta data tab -->
	<div id="page-meta">
		<ul>
			<li class="<?php echo alternator('even', ''); ?>">
				<label for="meta_title"><?php echo lang('pages.meta_title_label');?></label>
				<input type="text" id="meta_title" name="meta_title" maxlength="255" value="<?php echo $page->meta_title; ?>" />
			</li>
			<li class="<?php echo alternator('even', ''); ?>">
				<label for="meta_keywords"><?php echo lang('pages.meta_keywords_label');?></label>
				<input type="text" id="meta_keywords" name="meta_keywords" maxlength="255" value="<?php echo $page->meta_keywords; ?>" />
			</li>
			<li class="<?php echo alternator('even', ''); ?>">
				<label for="meta_description"><?php echo lang('pages.meta_desc_label');?></label>
				<?php echo form_textarea(array('name' => 'meta_description', 'value' => $page->meta_description, 'rows' => 5)); ?>
			</li>
		</ul>
	</div>

	<?php alternator(); ?>

	<!-- Design tab -->
	<div id="page-design">
		<ul>
			<li class="<?php echo alternator('even', ''); ?>">
				<label for="layout_id"><?php echo lang('pages.layout_id_label');?></label>
				<?php echo form_dropdown('layout_id', $page_layouts, $page->layout_id); ?>
			</li>
			<li class="<?php echo alternator('even', ''); ?>">
				<label for="css"><?php echo lang('pages.css_label');?></label>
				<div style="margin-left: 160px;">
					<?php echo form_textarea('css', $page->css, 'id="css_editor"'); ?>
				</div>
			</li>
		</ul>
		<br class="clear-both" />
	</div>

	<?php alternator(); ?>

	<!-- Script tab -->
	<div id="page-script">
		<ul>
			<li class="<?php echo alternator('', 'even'); ?>">
				<label for="js"><?php echo lang('pages.js_label'); ?></label>
				<div style="margin-left: 160px;">
					<?php echo form_textarea('js', $page->js, 'id="js_editor"'); ?>
				</div>
			</li>
		</ul>
		<br class="clear-both" />
	</div>

	<?php alternator(); ?>

	<!-- Options tab -->
	<div id="page-options">
		<ul>
			<li class="<?php echo alternator('even', ''); ?>">
				<label for="restricted_to[]"><?php echo lang('pages.access_label');?></label>
				<?php echo form_multiselect('restricted_to[]', array(0 => lang('select.any')) + $group_options, $page->restricted_to, 'size="'.(($count = count($group_options)) > 1 ? $count : 2).'"'); ?>
			</li>
			<li class="<?php echo alternator('even', ''); ?>">
				<label for="comments_enabled"><?php echo lang('pages.comments_enabled_label');?></label>
				<?php echo form_checkbox('comments_enabled', 1, $page->comments_enabled == 1); ?>
			</li>
			<li class="<?php echo alternator('even', ''); ?>">
				<label for="rss_enabled"><?php echo lang('pages.rss_enabled_label');?></label>
				<?php echo form_checkbox('rss_enabled', 1, $page->rss_enabled == 1); ?>
			</li>
			<li class="<?php echo alternator('even', ''); ?>">
				<label for="is_home"><?php echo lang('pages.is_home_label');?></label>
				<?php echo form_checkbox('is_home', 1, $page->is_home == 1); ?>
			</li>
		</ul>
	</div>

</div>

<div class="buttons">
	<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'save_exit', 'cancel') )); ?>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
css_editor('css_editor', '100%');
js_editor('js_editor', '100%');
</script>
