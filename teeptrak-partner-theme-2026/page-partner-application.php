<?php
/**
 * Template Name: Partner Application
 * @package TeepTrak_Partner_Theme_2026
 */

if (is_user_logged_in()) {
    wp_redirect(home_url('/dashboard/'));
    exit;
}

get_header();

$application_status = isset($_GET['application']) ? sanitize_text_field($_GET['application']) : '';
$errors = get_transient('teeptrak_application_errors');
$saved_data = get_transient('teeptrak_application_data');
delete_transient('teeptrak_application_errors');
delete_transient('teeptrak_application_data');
?>

<section class="tt-application-hero">
    <div class="tt-container">
        <h1><?php esc_html_e('Devenir Partenaire TeepTrak', 'teeptrak-partner'); ?></h1>
        <p><?php esc_html_e('Rejoignez +50 partenaires dans 30 pays qui aident les industriels a optimiser leur performance', 'teeptrak-partner'); ?></p>
    </div>
</section>

<section class="tt-application-content">
    <div class="tt-container">

        <?php if ($application_status === 'success') : ?>
            <div class="tt-application-success">
                <div class="tt-success-icon"><?php echo teeptrak_icon('check', 64); ?></div>
                <h2><?php esc_html_e('Candidature envoyee !', 'teeptrak-partner'); ?></h2>
                <p><?php esc_html_e('Merci pour votre candidature. Notre equipe vous contactera sous 2-3 jours ouvres.', 'teeptrak-partner'); ?></p>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="tt-btn tt-btn-primary">
                    <?php esc_html_e("Retour a l'accueil", 'teeptrak-partner'); ?>
                </a>
            </div>
        <?php else : ?>

            <div class="tt-application-grid">
                <!-- Sidebar Benefits -->
                <aside class="tt-application-sidebar">
                    <h3><?php esc_html_e('Pourquoi devenir partenaire ?', 'teeptrak-partner'); ?></h3>

                    <div class="tt-benefit-item">
                        <div class="tt-benefit-icon-small"><?php echo teeptrak_icon('dollar-sign', 24); ?></div>
                        <div>
                            <strong>15-30% de commission</strong>
                            <p>Commissions attractives selon votre niveau</p>
                        </div>
                    </div>

                    <div class="tt-benefit-item">
                        <div class="tt-benefit-icon-small"><?php echo teeptrak_icon('shield', 24); ?></div>
                        <div>
                            <strong>Protection 90 jours</strong>
                            <p>Vos opportunites sont protegees</p>
                        </div>
                    </div>

                    <div class="tt-benefit-item">
                        <div class="tt-benefit-icon-small"><?php echo teeptrak_icon('graduation-cap', 24); ?></div>
                        <div>
                            <strong>Certification gratuite</strong>
                            <p>Formation complete OEE et solutions</p>
                        </div>
                    </div>

                    <div class="tt-trust-badges">
                        <p class="tt-trust-label">ILS NOUS FONT CONFIANCE</p>
                        <div class="tt-trust-logos">
                            <span>STELLANTIS</span>
                            <span>RENAULT</span>
                            <span>ALSTOM</span>
                        </div>
                        <p class="tt-trust-stat">360+ usines dans 30+ pays</p>
                    </div>
                </aside>

                <!-- Application Form -->
                <div class="tt-application-form-wrapper">

                    <?php if (!empty($errors)) : ?>
                        <div class="tt-alert tt-alert-error">
                            <strong>Veuillez corriger les erreurs :</strong>
                            <ul>
                                <?php foreach ($errors as $error) : ?>
                                    <li><?php echo esc_html($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="tt-application-form">
                        <input type="hidden" name="action" value="teeptrak_partner_application">
                        <?php wp_nonce_field('teeptrak_application', 'teeptrak_application_nonce'); ?>

                        <!-- Personal Info -->
                        <fieldset class="tt-form-section">
                            <legend><?php esc_html_e('Informations personnelles', 'teeptrak-partner'); ?></legend>

                            <div class="tt-form-row tt-form-row-2">
                                <div class="tt-form-group">
                                    <label for="first_name">Prenom <span class="required">*</span></label>
                                    <input type="text" id="first_name" name="first_name" class="tt-form-control" required
                                           value="<?php echo esc_attr($saved_data['first_name'] ?? ''); ?>">
                                </div>
                                <div class="tt-form-group">
                                    <label for="last_name">Nom <span class="required">*</span></label>
                                    <input type="text" id="last_name" name="last_name" class="tt-form-control" required
                                           value="<?php echo esc_attr($saved_data['last_name'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="tt-form-row tt-form-row-2">
                                <div class="tt-form-group">
                                    <label for="email">Email professionnel <span class="required">*</span></label>
                                    <input type="email" id="email" name="email" class="tt-form-control" required
                                           value="<?php echo esc_attr($saved_data['email'] ?? ''); ?>">
                                </div>
                                <div class="tt-form-group">
                                    <label for="phone">Telephone <span class="required">*</span></label>
                                    <input type="tel" id="phone" name="phone" class="tt-form-control" required
                                           value="<?php echo esc_attr($saved_data['phone'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="tt-form-group">
                                <label for="password">Creer un mot de passe <span class="required">*</span></label>
                                <input type="password" id="password" name="password" class="tt-form-control" required minlength="8">
                                <span class="tt-form-hint">Minimum 8 caracteres</span>
                            </div>
                        </fieldset>

                        <!-- Company Info -->
                        <fieldset class="tt-form-section">
                            <legend><?php esc_html_e('Informations societe', 'teeptrak-partner'); ?></legend>

                            <div class="tt-form-row tt-form-row-2">
                                <div class="tt-form-group">
                                    <label for="company_name">Societe <span class="required">*</span></label>
                                    <input type="text" id="company_name" name="company_name" class="tt-form-control" required
                                           value="<?php echo esc_attr($saved_data['company_name'] ?? ''); ?>">
                                </div>
                                <div class="tt-form-group">
                                    <label for="job_title">Fonction</label>
                                    <input type="text" id="job_title" name="job_title" class="tt-form-control"
                                           value="<?php echo esc_attr($saved_data['job_title'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="tt-form-group">
                                <label for="country">Pays <span class="required">*</span></label>
                                <select id="country" name="country" class="tt-form-control" required>
                                    <option value="">Selectionnez...</option>
                                    <option value="FR" <?php selected($saved_data['country'] ?? '', 'FR'); ?>>France</option>
                                    <option value="BE" <?php selected($saved_data['country'] ?? '', 'BE'); ?>>Belgique</option>
                                    <option value="CH" <?php selected($saved_data['country'] ?? '', 'CH'); ?>>Suisse</option>
                                    <option value="CA" <?php selected($saved_data['country'] ?? '', 'CA'); ?>>Canada</option>
                                    <option value="DE" <?php selected($saved_data['country'] ?? '', 'DE'); ?>>Allemagne</option>
                                    <option value="OTHER" <?php selected($saved_data['country'] ?? '', 'OTHER'); ?>>Autre</option>
                                </select>
                            </div>
                        </fieldset>

                        <!-- Partner Profile -->
                        <fieldset class="tt-form-section">
                            <legend><?php esc_html_e('Profil partenaire', 'teeptrak-partner'); ?></legend>

                            <div class="tt-form-group">
                                <label>Type de partenaire <span class="required">*</span></label>
                                <div class="tt-radio-group">
                                    <label class="tt-radio-card">
                                        <input type="radio" name="partner_type" value="integrator" required>
                                        <span class="tt-radio-content">
                                            <strong>Integrateur Systemes</strong>
                                            <span>MES/ERP, automatisation industrielle</span>
                                        </span>
                                    </label>
                                    <label class="tt-radio-card">
                                        <input type="radio" name="partner_type" value="var" required>
                                        <span class="tt-radio-content">
                                            <strong>Revendeur VAR</strong>
                                            <span>Distribution equipements industriels</span>
                                        </span>
                                    </label>
                                    <label class="tt-radio-card">
                                        <input type="radio" name="partner_type" value="consultant" required>
                                        <span class="tt-radio-content">
                                            <strong>Consultant</strong>
                                            <span>Lean/Six Sigma, excellence operationnelle</span>
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <div class="tt-form-group">
                                <label>Secteurs cibles</label>
                                <div class="tt-checkbox-grid">
                                    <?php
                                    $industries = array(
                                        'automotive' => 'Automobile',
                                        'aerospace' => 'Aeronautique',
                                        'food' => 'Agroalimentaire',
                                        'pharma' => 'Pharmaceutique',
                                        'electronics' => 'Electronique',
                                        'other' => 'Autre'
                                    );
                                    foreach ($industries as $value => $label) :
                                    ?>
                                        <label class="tt-checkbox">
                                            <input type="checkbox" name="target_industries[]" value="<?php echo esc_attr($value); ?>">
                                            <span><?php echo esc_html($label); ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="tt-form-group">
                                <label for="manufacturing_experience">Experience industrielle</label>
                                <textarea id="manufacturing_experience" name="manufacturing_experience" class="tt-form-control" rows="3"
                                          placeholder="Decrivez votre experience avec les clients industriels..."></textarea>
                            </div>
                        </fieldset>

                        <!-- Submit -->
                        <div class="tt-form-submit-section">
                            <label class="tt-checkbox tt-terms-checkbox">
                                <input type="checkbox" name="accept_terms" required>
                                <span>J'accepte les <a href="#" target="_blank">conditions du programme partenaire</a></span>
                            </label>

                            <button type="submit" class="tt-btn tt-btn-primary tt-btn-xl tt-btn-full">
                                Envoyer ma candidature
                            </button>

                            <p class="tt-form-footer-text">
                                Deja partenaire ? <a href="<?php echo esc_url(wp_login_url(home_url('/dashboard/'))); ?>">Connectez-vous</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>

        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
