<?php
class CRUDStatutSemestre extends Module{

	public function action_index(){

		$this->set_title("Liste");

		//création de variables PHP
		//on récupère de la base de données des éléments
		$data = StatutSemestreManager::listerStatutSemestre();
					
		//passe les variables au template		
		$this->tpl->assign('data',$data);

	}
	
	public function action_modifier(){
		
		//recupère l'id
		$id = $this->req->id;
		
		$data = StatutSemestreManager::chercherParID($id);
		
		$this->set_title("Modifier"); //OK
		$f=new Form("?module=CRUDStatutSemestre&action=valide&mod=modifier&id=".$id,"form update"); //OK
		$f->add_text("nomStatutSemestre","nomStatutSemestre","Statut de Semestre")-> set_required(); //OK
		$f->add_submit("Valider","bntval")->set_value('Modifier');		
		
		// Remet les valeurs d'origine (pré-remplissage)
		$f->populate(array("nomStatutSemestre" => $data->nomStatutSemestre));
							
		//passe les variables au template
		$this->tpl->assign('data',$data);
		$this->tpl->assign('form',$f);
		$this->session->form = $f;

	}
	
	public function action_valide(){
		
                $id = $this->req->id;
                $mod = $this->req->mod;
		$mb = new StatutSemestreManager();

		$this->set_title("Validation");
		$err=false;
		//on récupère la structure du formulaire précédemment stocké dans la session
		$form=$this->session->form;
		$form->reset_errors();
		
		//faire les tests de vérification de remplissage/format des champs
		//... expressions régulières, etc.
	
	
		//On vérifie si chaque champ correspond aux règles
		if($this->req->nomStatutSemestre == ''){
			$this->site->ajouter_message('Veuillez saisir un nom valide',ALERTE);			
			$err=true;
			$form->nomStatutSemestre->set_error(true);
			$form->nomStatutSemestre->set_error_message("champs vide !");
		}
                
		//si un des tests a échoué
		if($err){	
			//on pré-remplit avec les valeurs déjà saisies
			$form->populate();		
			//passe le formulaire dans le template sous le nom "form"
			$this->tpl->assign("form",$form);
		}
		//tous les tests ont été validés
		else{
			//création d'une instance de StatutSemestre
			$m=new StatutSemestre();
			$m->nomStatutSemestre=$this->req->nomStatutSemestre;
			
			//enregistrement (insertion) dans la base
                        if(isset($mod) && $mod== "modifier") {
                            
                            $mb->modifierStatutSemestre($m, $id);
                            //passe un message pour la page suivante
                            $this->site->ajouter_message('Le statut de semestre a bien été modifié');
                            //redirige vers le module par défaut
                            $this->site->redirect('CRUDStatutSemestre');
                        }
                        elseif(isset($mod) && $mod=="ajouter"){		
                            
                            $mb->creerStatutSemestre($m);
                            //passe un message pour la page suivante
                            $this->site->ajouter_message('Le statut de semestre a bien été créé');
                            //redirige vers le module par défaut
                            $this->site->redirect('CRUDStatutSemestre');
                        }
		}
	}
	
	public function action_supprimer(){
		$this->set_title("Supprimer");

		//recupère l'id
		$id = $this->req->id;
		
		//passe ces informations dans le template
		
		$User = new StatutSemestreManager();
		
		$User->supprimerStatutSemestre($id);
		$this->site->ajouter_message('Le statut de semestre a bien été supprimé');
		$this->site->redirect('CRUDStatutSemestre');
		
	}
	

	public function action_ajouter(){
            
            $this->set_title("Ajouter"); //OK
		$f=new Form("?module=CRUDStatutSemestre&action=valide&mod=ajouter","form_ajouterUser"); //OK
		$f->add_text("nomStatutSemestre","nomStatutSemestre","Nom")-> set_required(); //OK
		$f->add_submit("Valider","bntval")->set_value('Valider');		

		// on peut pré-remplir le formulaire avec des valeurs par défaut
		//$f->populate(array("choix"=>"Deux", "rad1"=>"two", "nom"=>"Nom de Famille"));


		//passe le formulaire dans le template sous le nom "form"
		$this->tpl->assign("form",$f);	
		//stocke la structure du formulaire dans la session sous le nom "form"
		$this->session->form = $f;
		
	}

	public function action_detail(){
		$this->set_title("Détail");	
		
		//recupère l'id et la référence 
		$id = $this->req->id;
		
		$data = StatutSemestreManager::chercherParID($id);
		//passe les variables au template		
		$this->tpl->assign('data',$data);
		
		/*$this->tpl->assign("id",$id);
		$this->tpl->assign("nomStatutSemestre",$nomStatutSemestre);
		$this->tpl->assign("prenomStatutSemestre",$prenomStatutSemestre);	*/
	}
		
}	
?>